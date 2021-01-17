<?php

namespace orderhandling\Classes;

use orderhandling\Classes\Models\Product;
use orderhandling\Classes\Validator;
use orderhandling\Classes\SumInterface;

class ProductController extends Validator implements SumInterface
{

    /**
     * @param float $basePrice
     * @param float $taxPrice
     * @param float $quantity
     * @return float
     */
    public function calculateTotal(float $basePrice, float $taxPrice, float $quantity): float
    {
        return round(($basePrice + $taxPrice) * $quantity, 2);
    }

    /**
     * @param float $basePrice
     * @param int $taxRate
     * @return float|int
     */
    public function taxPrice(float $basePrice, int $taxRate) {
        return ($basePrice * $taxRate) / 100;
    }

    /**
     * Add to invoice sum if sum is less than defined
     *
     * @param float $invoiceSum
     * @return bool
     */
    public function addExtraIfLessThan(float $invoiceSum): bool
    {
        return ($invoiceSum < self::MINIMUM_INVOICE_SUM);
    }

    /**
     * Gets product quantity from the productlist array
     *
     * @param array $productLists
     * @param string $key
     * @return float
     */
    public function getQuantity(array $productLists, string $key): float
    {
        $quantity = $productLists[$key];
        return floatval($quantity);
    }


    /**
     * @param $data
     * @return array
     */
    public function parseData($data): array
    {
        return array(
            json_decode(json_encode($data->product_list),true),
            $data->country_code,
            $data->invoice_format,
            $data->return_type,
            isset($data->email) ? $data->email : '',
        );
    }



    /**
     * process an invoice
     *
     * @param $data
     * @return false|string
     */
    public function processInvoice($data)
    {

        $isValidData =  $this->validateData($data);
        if ($isValidData !== TRUE) {
            return $isValidData;
        }

        list($productLists, $countryCode, $invoiceFormat, $returnType, $email) = $this->parseData($data);

        $data = '';
        $invoiceDetails = $this->getInvoiceDetails($productLists, $countryCode);

        //TODO:: Need to handle if no product is found

        switch(strtolower($invoiceFormat)) {
            case self::INOVICE_FORMAT_JSON:
                $data = json_encode($invoiceDetails, JSON_PRETTY_PRINT);
                break;
            case self::INOVICE_FORMAT_HTML:
            default:
                $data = $this->invoiceInHTML($invoiceDetails);
        }

        return $this->invoiceResponse($data, $invoiceFormat, $returnType, $email);
    }


    /**
     * Final invoice response
     *
     * @param string $data
     * @param string $invoiceFormat
     * @param string $returnType
     * @param string $email
     * @return false|string
     */
    public function invoiceResponse(string $data, string $invoiceFormat, string $returnType, string $email = '')
    {
        switch(strtolower($returnType)) {
            case self::INVOICE_RESPONSE_EMAIL:
                return $this->sendEmail($data, $email);
            case self::INVOICE_RESPONSE_JSON:
            default:
                return $this->getResponseData($data, $invoiceFormat);
        }
    }

    /**
     * @param string $data
     * @param string $invoiceFormat
     * @return false|string
     */
    public function getResponseData(string $data, string $invoiceFormat)
    {
        $result = (strtolower($invoiceFormat) === self::INOVICE_FORMAT_JSON) ? json_decode($data, true) : $data;

        return json_encode(
            array('data' => $result), JSON_PRETTY_PRINT
        );
    }


    /**
     * Generate invoice details to process further
     *
     * @param array $productLists
     * @param string $countryCode
     * @return array
     */
    public function getInvoiceDetails(array $productLists, string $countryCode)
    {

        $productNames = array_keys($productLists);
        $product = new Product();
        $products =  $product->getProducts($productNames, $countryCode);

        $invoiceSum = 0;
        $invoiceTax = 0;
        $productsWithTotal = array();
        $currencySymbol = '';

        if (count($products)) {

            foreach ($products as $p) {

                $name = $p['name'];
                $quantity = $this->getQuantity($productLists, $name);
                $basePrice = floatval($p['price']);
                $taxRate = intval($p['tax_rate']);
                $taxPrice = $this->taxPrice($basePrice, $taxRate);
                $totalPrice = $this->calculateTotal($basePrice, $taxPrice, $quantity);
                $currencySymbol = $p['currency'];

                array_push($productsWithTotal, array(
                    'product_name' => $name,
                    'product_code' => $p['code'],
                    'quantity' => $quantity,
                    'tax_rate' => $taxRate,
                    'tax_price' => $taxPrice,
                    'base_price' => $basePrice,
                    'total_price' => $totalPrice,
                    'currency' => $currencySymbol
                ));

                $invoiceTax += ($taxPrice * $quantity);
                $invoiceSum += $totalPrice;
            }

            return array (
                'invoice_products' => $productsWithTotal,
                'invoice_tax' => $invoiceTax,
                'invoice_sum' => $this->getInvoiceSum($invoiceSum),
                'invoice_note' => $this->getInvoiceNote($invoiceSum, $currencySymbol)
            );
        }

        return NULL;
    }


    /**
     * Gets an invoice sum
     * @param float $invoiceSum
     * @return float
     */
    public function getInvoiceSum(float $invoiceSum)
    {
        $sum = ($this->addExtraIfLessThan($invoiceSum)) ? $invoiceSum + 10 : $invoiceSum;
        return round($sum, 2);
    }


    /**
     * Gets an invoice note
     *
     * @param float $invoiceSum
     * @param string $currencySymbol
     * @return string|string[]|null
     */
    public function getInvoiceNote(float $invoiceSum, string $currencySymbol)
    {
        return ($this->addExtraIfLessThan($invoiceSum)) ? str_replace('{currency}', $currencySymbol, self::MINIMUM_INVOICE_SUM_MESSAGE) : NULL;
    }


    /**
     * Generate HTML for invoice to send with an email
     * @param array $invoiceDetails
     * @return string
     */
    public function invoiceInHTML(array $invoiceDetails)
    {
        $html = '<table>
                    <thead>
                        <tr>
                            <th>Name (Code)</th>
                            <th>Tax rate</th>
                            <th>Quantity</th>
                            <th>Base price</th>
                            <th>Tax price</th>
                            <th>Price</th>
                        </tr>
                      </thead>';

        $invoiceProducts = $invoiceDetails['invoice_products'];
        foreach($invoiceProducts as $product) {

            $html .= "<tr>
                        <td>{$product['product_name']} ({$product['product_code']})</td>
                        <td>{$product['tax_rate']}</td>
                        <td>{$product['quantity']}</td>
                        <td>{$product['base_price']}</td>
                        <td>{$product['tax_price']}</td>
                        <td>{$product['total_price']}</td>
                    </tr>";
        }

        $html .= "<tfoot>
                    <tr>
                        <td colspan=\"4\"></td>
                        <td>{$invoiceDetails['invoice_tax']}</td>
                        <td>{$invoiceDetails['invoice_sum']}</td>
                    </tr>";

        if (! is_null($invoiceDetails['invoice_note'])) {
            $html .= "
                    <tr>
                        <td colspan=\"6\"><b>{$invoiceDetails['invoice_note']}</b></td>
                    </tr>";
        }

        $html .= "</tfoot>
            </table>";

        return $html;
    }

    /**
     * Send an email
     * @param string $emailBody
     * @param string $email
     * @return string
     */
    public function sendEmail(string $emailBody, string $email)
    {

        try {
            $subject = "HTML email";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <webmaster@example.com>' . "\r\n";

            mail($email,$subject,$emailBody,$headers);

            return $emailBody;

        } catch (Exception $e) {

            return self::getErrorResponse('EMAIL_SEND_FAILED');
        }
    }

}