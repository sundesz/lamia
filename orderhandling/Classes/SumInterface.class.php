<?php

namespace orderhandling\Classes;

interface SumInterface
{
    public function taxPrice(float $basePrice, int $taxRate);

    public function calculateTotal(float $basePrice, float $taxPrice, float $quantity);

    public function addExtraIfLessThan(float $invoiceSum);
}