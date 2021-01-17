<?php

namespace orderhandling\Classes\Models;

use orderhandling\Classes\Lib\Table;

/**
 * NOT IN USE
 *
 * Class Country
 * @package OrderHandling\Classes\Models
 */
class Country extends Table
{

    protected $countryId;
    protected $name;
    protected $shortName;
    protected $currencyCode;
    protected $currencySymbol;

    public function __construct()
    {
        $this->table = 'country';
        $this->conn = $this->connect();
    }

    public function getCountryid()
    {
        return $this->countryId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getShortname()
    {
        return $this->shortName;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }

}