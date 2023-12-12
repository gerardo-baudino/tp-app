<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nro_cliente',
        'cuit_cliente',
        'razon_social',
        'ytd',
        'tier',
    ];

    // Restricciones de longitud y tipo de dato
    public static $lengthConstraints = [
        'nro_cliente' => 11,
        'cuit_cliente' => 11,
        'razon_social' => 30,
    ];

    public static $dataTypes = [
        'nro_cliente' => 'integer',
        'cuit_cliente' => 'numeric',
        'razon_social' => 'string',
    ];

    // Relación con las importaciones de ventas
    public function importVentas()
    {
        return $this->hasMany(ImportVentas::class);
    }
}
