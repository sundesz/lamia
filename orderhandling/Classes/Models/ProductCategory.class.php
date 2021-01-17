<?php

namespace orderhandling\Classes\Models;

use orderhandling\Classes\Lib\Table;

/**
 * NOT IN USE
 *
 * Class ProductCategory
 * @package OrderHandling\Classes\Models
 */
class ProductCategory extends Table
{

    protected $productCategoryId;
    protected $name;

    public function __construct()
    {
        $this->table = 'product_category';
        $this->conn = $this->connect();
    }


    public function getProductcategoryid()
    {
        return $this->productCategoryId;
    }


    public function getName()
    {
        return $this->name;
    }

}