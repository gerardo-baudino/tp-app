<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportVentas extends Model
{
    protected $fillable = [
        'fecha',
        'tipo_comprobante',
        'numero_punto_venta',
        'numero_comprobante',
        'importe_venta',
        'cuit_cliente',
        'razon_social',
        'nro_cliente',
    ];

    // Define la relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
