<?php 

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\ImportVentas;

class VentasImport implements ToModel, WithHeadingRow
 {
    public function model( array $row )
 {
        // Implementa la lógica para mapear los datos del archivo a la tabla import_ventas
        return new ImportVentas( [
            'fecha' => $row[ 'fecha' ],
            'tipo_comprobante' => $row[ 'tipo_comprobante' ],
            'punto_venta' => $row[ 'punto_venta' ],
            'numero_comprobante' => $row[ 'numero_comprobante' ],
            'importe_venta' => $row[ 'importe_venta' ],
            'cuit_cliente' => $row[ 'cuit_cliente' ],
            'razon_social' => $row[ 'razon_social' ],
            'nro_cliente' => $row[ 'nro_cliente' ],
        ] );
    }
}
