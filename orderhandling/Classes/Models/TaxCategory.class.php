<?php

namespace orderhandling\Classes\Models;

use orderhandling\Classes\Lib\Table;

/**
 * NOT IN USE
 *
 * Class TaxCategory
 * @package OrderHandling\Classes\Models
 */
class TaxCategory extends Table
{

    protected $taxCategoryId;
    protected $name;

    public function __construct()
    {
        $this->table = 'tax_category';
        $this->conn = $this->connect();
    }


    public function getTaxcategoryid()
    {
        return $this->taxCategoryId;
    }


    public function getName()
    {
        return $this->name;
    }


}