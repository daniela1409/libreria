<?php

namespace App\Services;

interface ISale
{
    public function findAllSales();
    public function findOneSale($saleId);
    public function saveSale($params);
}