<?php

namespace orderhandling\Classes\Models;

use orderhandling\Classes\Lib\Table;

/**
 * NOT IN USE
 *
 * Class TaxRate
 * @package OrderHandling\Classes\Models
 */
class TaxRate extends Table
{

    protected $taxRateId;
    protected $countryId;
    protected $taxCategoryId;
    protected $taxRate;

    public function __construct()
    {
        $this->table = 'tax_rate';
        $this->conn = $this->connect();
    }


    public function getTaxrateid()
    {
        return $this->taxRateId;
    }


    public function getCountryid()
    {
        return $this->countryId;
    }


    public function getTaxcategoryid()
    {
        return $this->taxCategoryId;
    }


    public function getTaxrate()
    {
        return $this->taxRate;
    }

}