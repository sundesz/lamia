<?php

namespace orderhandling\Classes\Models;

use orderhandling\Classes\Lib\Table;

class Product extends Table
{

    protected $productId;
    protected $name;
    protected $code;
    protected $productCategoryId;
    protected $taxCategoryId;

    protected $price;
    protected $taxRate;

    public function __construct()
    {
        $this->table = 'product';
        $this->conn = $this->connect();
    }

    public function getProductid()
    {
        return $this->productId;
    }


    public function getName()
    {
        return $this->name;
    }


    public function getCode()
    {
        return $this->code;
    }


    public function getProductcategoryid()
    {
        return $this->productCategoryId;
    }



    public function getTaxcategoryid()
    {
        return $this->taxCategoryId;
    }


    public function getPrice()
    {
        return $this->price;
    }


    public function getTaxrate()
    {
        return $this->taxRate;
    }


    public function getProducts(array $productNames, string $countryCode = 'FI')
    {

        $productNamesWhereIn = "'" . implode("', '", $productNames) . "'";
        $sql = "SELECT
        	        p.product_id,
	                p.name, 
	                p.code,
	                pp.price,
	                tr.tax_rate as tax_rate,
                    c.currency_symbol as currency
                FROM
                    product p, 
                    product_price pp,
                    tax_rate tr, 
                    country c
                WHERE
	                p.product_id = pp.product_id AND
	                p.tax_category_id = tr.tax_category_id AND 
	                tr.country_id = c.country_id AND
	                pp.country_id = c.country_id AND
	                c.short_name = :country AND
	                p.name IN ($productNamesWhereIn)
                -- LIMIT 10
                ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':country', $countryCode);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}