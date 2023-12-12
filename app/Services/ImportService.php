<?php

namespace App\Services;

class ImportService
{
    public function guardar($importVentas)
    {
        $importVentas->save();
    }
}
