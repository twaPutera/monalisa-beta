<?php

namespace App\Services\PemindahanAsset;

use Exception;

class PemindahanDatatableServices
{
    protected $property1;

    public function __construct($property1 = null)
    {
        $this->property1 = $property1;
    }

    public function getProperty1()
    {
        return $this->property1;
    }

    public function setProperty1($property1)
    {
        $this->property1 = $property1;
    }
}
