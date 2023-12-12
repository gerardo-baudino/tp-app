<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cliente;
use App\Services\ClienteService;

class ClienteController extends Controller
{
    protected $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function mostrarFormulario()
    {
        Log::debug('Ingresó a mostrarFormulario');
        return view('cliente.form');
    }

    public function todos(Request $request)
    {
        Log::debug('Ingresó a todos');
        $clientes = $this->clienteService->todos();

        // Verificar si se encontraron clientes
        if ($clientes->count() > 0) {
            Log::debug('Se encontraron clientes');
            // Puedes redirigir a la vista con los datos encontrados
            return view('cliente.form', compact('clientes'));
        } else {
            Log::debug('No se encontraron clientes');
            return view('cliente.form');
        }
    }

    public function buscarPorCuit(Request $request)
    {
        Log::debug('Ingresó a buscar');
        $clienteEncontrado = $this->clienteService->buscarPorCuit($request->input('cuit_cliente'));

        if ($clienteEncontrado) {
            Log::debug('Se encontro el cliente solicitado');
            // Obtener todos los clientes de la base de datos
            $clientes = Cliente::all();
            return view('cliente.form', compact('clientes', 'clienteEncontrado'));
        } else {
            Log::debug('No se encontro cliente');
            return redirect()->back()->with('error', 'Cliente no encontrado para el CUIT proporcionado');
        }
    }

    public function crear(Request $request)
    {
        try {
            Log::debug('Ingresó a crear');
            $nuevoCliente = $this->clienteService->crear(
                $request->input('cuit_cliente'),
                $request->input('razon_social'),
                $request->input('nro_cliente')
            );

            // Verificar si la creación fue exitosa
            if ($nuevoCliente) {
                Log::debug('Cliente creado exitosamente');
                return redirect()->back()->with('success', 'Cliente creado exitosamente');
            } else {
                Log::error('Error al crear el cliente');
                return redirect()->back()->with('error', 'Error al crear el cliente. Por favor, intenta nuevamente.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessage = $e->getMessage();
            Log::error('Error al crear el cliente. Error: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al crear el cliente. Msg (' . $errorMessage . ')');
        }
    }

    public function actualizar(Request $request, $id)
    {
        try {
            Log::debug('Ingresó a actualizar. Id: ' . $id);
            $clienteModificado = $this->clienteService->actualizar(
                $id,
                $request->cuit_cliente,
                $request->razon_social,
                $request->nro_cliente,
            );

            // Verificar si la modificación fue exitosa
            if ($clienteModificado) {
                Log::debug('Cliente modificado exitosamente');
                return redirect('/clientes')->with('success', 'Cliente modificado exitosamente');
            } else {
                Log::error('Error al modificar el cliente');
                return redirect('/clientes')->with('error', 'Error al modificar el cliente. Por favor, intenta nuevamente.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessage = $e->getMessage();
            Log::error('Error al modificar el cliente. Error: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al modificar el cliente. Msg (' . $errorMessage . ')');
        }
    }
}
