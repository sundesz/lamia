<?php

namespace orderhandling\Classes\Models;

use orderhandling\Classes\Lib\Table;


/**
 * NOT IN USE
 *
 * Class ProductPrice
 * @package OrderHandling\Classes\Models
 */
class ProductPrice extends Table
{

    protected $productPriceId;
    protected $countryId;
    protected $productId;
    protected $price;

    public function __construct()
    {
        $this->table = 'product_price';
        $this->conn = $this->connect();
    }


    public function getProductpriceid()
    {
        return $this->productPriceId;
    }



    public function getCountryid()
    {
        return $this->countryId;
    }



    public function getProductid()
    {
        return $this->productId;
    }



    public function getPrice()
    {
        return $this->price;
    }

}