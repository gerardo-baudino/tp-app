<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ImportVentas;
use App\Models\Cliente;
use App\Services\ClienteService;
use App\Services\ImportService;
use Carbon\Carbon;

class ImportController extends Controller
{
    protected $importService;
    protected $clienteService;

    public function __construct(ImportService $importService, ClienteService $clienteService)
    {
        $this->importService = $importService;
        $this->clienteService = $clienteService;
    }

    public function mostrarFormulario()
    {
        Log::debug('Ingresó a mostrarFormulario');
        return view('import.form');
    }

    public function importarVentas(Request $request)
    {
        try {
            Log::debug('Ingresó a importarVentas');
            $request->validate([
                'archivo_csv' => 'required|mimes:css,txt|max:10240',
            ]);

            if ($request->hasFile('archivo_csv')) {
                Log::debug('El archivo existe');
                $archivo = $request->file('archivo_csv');
                // Almacena el archivo temporalmente en la carpeta 'temp'
                $path = $archivo->store('temp');
                $contenido = file_get_contents(storage_path("app/{$path}"));
                $filas = explode("\n", $contenido);
                $errores = [];
                $exitos = [];

                foreach ($filas as $fila) {
                    $datos = str_getcsv($fila, ';');

                    // Ignora la primera fila (encabezados)
                    if (count($datos) === 8 && $datos[0] !== 'Fecha') {
                        $fecha = $datos[0];
                        $tipoComprobante = $datos[1];
                        $numeroComprobante = $datos[3];
                        $cuitCliente = $datos[5];
                        $nroCliente = $datos[7];

                        $fechaActual = now();
                        $fechaComprobante = Carbon::createFromFormat('d/m/Y', $fecha);
                        if ($fechaComprobante->gt($fechaActual)) {
                            Log::debug('Fecha futura para el comprobante');
                            $errores[] = 'Fecha futura para el comprobante revisar la fila: ' . implode(', ', $datos);
                            continue;
                        }

                        // Validar si ya existe un comprobante con el mismo Tipo y Número
                        $comprobanteExistente = ImportVentas::where([
                            'tipo_comprobante' => $tipoComprobante,
                            'numero_comprobante' => $numeroComprobante,
                        ])->first();

                        if ($comprobanteExistente) {
                            Log::debug('Comprobante repetido');
                            $errores[] = 'Ya existe un comprobante con el mismo Tipo y Número revisar la fila: ' . implode(', ', $datos);
                            continue;
                        }

                        if ($numeroComprobante == 0) {
                            Log::debug('Numero de comprobante es 0');
                            $errores[] = 'Número de comprobante 0 revisar la fila: ' . implode(', ', $datos);
                            continue;
                        }

                        $importVentas = new ImportVentas([
                            'fecha' => $fecha,
                            'tipo_comprobante' => $tipoComprobante,
                            'numero_punto_venta' => $datos[2],
                            'numero_comprobante' => $numeroComprobante,
                            'importe_venta' => $datos[4],
                            'cuit_cliente' => $cuitCliente,
                            'razon_social' => $datos[6],
                            'nro_cliente' => $nroCliente,
                        ]);

                        if ($this->cargarCliente($importVentas)) {
                            $this->importService->guardar($importVentas);
                            $exitos[] = 'Fila registrada con exito: ' . implode(', ', $datos);
                        } else {
                            Log::debug('No se pudo crear el Cliente');
                            $errores[] = 'Error al crear el Cliente revisar la fila: ' . implode(', ', $datos);
                            continue;
                        }
                    }
                }

                if (!empty($errores) || !empty($exitos)) {
                    $data = [
                        'errores' => $errores,
                        'exitos' => $exitos,
                    ];                
                    return redirect()->back()->with($data)->withInput();
                }
            } else {
                Log::debug('El archivo no existe');
                return redirect()->back()->with('error', 'Error el archivo no existe. Por favor, intenta nuevamente.');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            Log::error('Error al procesar el archivo. Error: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al procesar el archivo. Msg (' . $errorMessage . ')');
        }
    }

    private function cargarCliente($venta)
    {
        try {
            Log::debug('Ingresó a cargarCliente');
            $this->clienteService->crearDesdeArchivo($venta->cuit_cliente, $venta->razon_social, $venta->nro_cliente);
            return true;
        } catch (\Exception $e) {
            Log::error('Error en cargarCliente: ' . $e->getMessage());
            return false;
        }
    }
}
