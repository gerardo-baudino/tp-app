<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\ImportVentas;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ClienteService
{
    public function todos()
    {
        // Obtener todos los clientes de la base de datos
        return Cliente::all();
    }

    public function buscarPorCuit($cuit)
    {
        // Validar el formulario de búsqueda
        $reglas = [
            'cuit_cliente' => 'required|numeric|digits:11',
        ];

        // Validar los datos
        $validador = Validator::make([
            'cuit_cliente' => $cuit,
        ], $reglas);

        // Lanzar una excepción si la validación falla
        if ($validador->fails()) {
            throw ValidationException::withMessages($validador->errors()->toArray());
        }

        // Buscar el cliente por CUIT en la base de datos
        return Cliente::where('cuit_cliente', $cuit)->first();
    }

    public function crearDesdeArchivo($cuit, $razonSocial, $nroCliente)
    {
        $clienteExistente = Cliente::where('cuit_cliente', $cuit)->orWhere('nro_cliente', $nroCliente)->first();

        if ($clienteExistente) {
            Log::debug('Cliente con CUIT o Nro de Cliente duplicado');
            // Obtener las ventas acumuladas del último año a la fecha
            $ventasUltimoAno = ImportVentas::where('cuit_cliente', $cuit)
                ->where('fecha', '>=', now()->subYear())
                ->sum('importe_venta');

            Log::debug('Ventas del último año: ' . $ventasUltimoAno);

            // Actualizar el campo YTD
            $clienteExistente->update(['ytd' => $ventasUltimoAno]);

            // Calcular el tier según la cantidad de ventas
            $tier = 1;
            if ($ventasUltimoAno > 1000000 && $ventasUltimoAno <= 3000000) {
                $tier = 2;
            } elseif ($ventasUltimoAno > 3000000) {
                $tier = 3;
            }

            // Actualizar el campo tier
            $clienteExistente->update(['tier' => $tier]);
        } else {
            $this->crear($cuit, $razonSocial, $nroCliente);
        }
    }

    public function crear($cuit, $razonSocial, $nroCliente)
    {
        $this->validarDatos($cuit, $razonSocial, $nroCliente);

        $cliente = Cliente::create([
            'cuit_cliente' => $cuit,
            'razon_social' => $razonSocial,
            'nro_cliente' => $nroCliente,
            'tier' => 1,
        ]);

        return $cliente;
    }

    public function actualizar($id, $cuit, $razonSocial, $nroCliente)
    {
        $this->validarDatos($cuit, $razonSocial, $nroCliente, false, $id);

        // Obtener el cliente por su ID
        $cliente = Cliente::find($id);

        $clienteModificado = $cliente->update([
            'cuit_cliente' => $cuit,
            'razon_social' => $razonSocial,
            'nro_cliente' => $nroCliente,
        ]);

        return $clienteModificado;
    }

    public function validarDatos($cuit, $razonSocial, $nroCliente, $esNuevo = true, $id = null)
    {
        if ($esNuevo) {
            // Definir reglas de validación
            $reglas = [
                'nro_cliente' => 'required|integer|unique:clientes',
                'cuit_cliente' => 'required|numeric|digits:11|unique:clientes',
                'razon_social' => 'required|max:30',
            ];
        } else {
            $reglas = [
                'nro_cliente' => 'required|integer|unique:clientes,nro_cliente,' . $id,
                'cuit_cliente' => 'required|numeric|unique:clientes,cuit_cliente,' . $id,
                'razon_social' => 'required|max:30',
            ];
        }

        // Definir mensajes de error personalizados
        $mensajes = [
            'nro_cliente.unique' => 'El número de cliente ya está en uso',
            'cuit_cliente.unique' => 'El CUIT ya está en uso',
        ];

        // Validar los datos
        $validador = Validator::make([
            'nro_cliente' => $nroCliente,
            'cuit_cliente' => $cuit,
            'razon_social' => $razonSocial,
        ], $reglas, $mensajes);

        // Lanzar una excepción si la validación falla
        if ($validador->fails()) {
            throw ValidationException::withMessages($validador->errors()->toArray());
        }
    }
}
