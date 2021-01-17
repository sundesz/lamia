<?php

namespace orderhandling\Classes;

use orderhandling\Classes\Constant\Product as ProductConstants;

class Validator extends ProductConstants
{
    /**
     * types for invoice_format
     */
    CONST INVOICE_FORMAT = ['json', 'html'];

    /**
     * types for return_type
     */
    CONST RETURN_TYPE = ['json', 'email'];

    /**
     * validate data before processing
     *
     * @param $data
     * @return bool|string
     */
    public function validateData($data)
    {

        if (is_null($data)) {
            return self::getErrorResponse('GENERAL');
        }

        if (! $this->validateArray($data->product_list)) {
            return self::getErrorResponse('PRODUCT');
        }

        if (! $this->validateString($data->country_code)) {
            return self::getErrorResponse('COUNTRY');
        }

        if (! $this->validateString($data->invoice_format)) {
            return self::getErrorResponse('INVOICE_FORMAT');
        }

        if (! $this->validateString($data->return_type)) {
            return self::getErrorResponse('RETURN_TYPE');
        }

        if (strtolower($data->return_type) === ProductConstants::INVOICE_RESPONSE_EMAIL) {
            if (! $this->validateEmail($data->email)) {
                return self::getErrorResponse('EMAIL');
            }
        }

        return TRUE;
    }


    /**
     * Validate String
     *
     * TODO::May be check if string exists in array
     *
     * @param $string
     * @return bool
     */
    public function validateString($string)
    {
        return (isset($string) && ctype_alnum($string));
    }

    /**
     * Validate Array
     *
     * @param $array
     * @return bool
     */
    public function validateArray($array)
    {
        if (isset($array)) {
            $lists = json_decode(json_encode($array),true);
            if (is_array($lists)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Validate email
     *
     * @param $email
     * @return bool
     */
    public function validateEmail($email)
    {
        return (isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL));
    }


    /**
     * Get reponse for error
     *
     * @param string $codeType
     * @return false|string
     */
    public static function getErrorResponse(string $codeType)
    {
        $const_prefix = ProductConstants::ERROR_CODE_PREFIX;
        $constant = 'ProductConstants::' . $const_prefix . strtoupper($codeType);
        $error_code = constant($constant);
        $error_message = constant($constant . '_MESSAGE');

        return json_encode(array(
            'error_code' => $error_code,
            'error_message' => $error_message
        ), JSON_PRETTY_PRINT);
    }

}