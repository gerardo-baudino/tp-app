<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Models\ImportVentas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrearClientesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $venta;

    /**
     * Create a new job instance.
     */
    public function __construct(ImportVentas $venta)
    {
        Log::debug('Ingresó a CrearClientesJob');
        $this->venta = $venta;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::debug('Ingresó a handle');
            $cliente = Cliente::updateOrCreate(
                ['cuit_cliente' => $this->venta->cuit_cliente],
                ['razon_social' => $this->venta->razon_social, 'nro_cliente' => $this->venta->nro_cliente]
            );

            // Obtener las ventas acumuladas del último año a la fecha
            $ventasUltimoAno = ImportVentas::where('cuit_cliente', $this->venta->cuit_cliente)
                ->where('fecha', '>=', now()->subYear())
                ->sum('importe_venta');

            // Actualizar el campo YTD
            $cliente->update(['ytd' => $ventasUltimoAno]);

            // Calcular el tier según la cantidad de ventas
            $tier = 1;
            if ($ventasUltimoAno > 1000000 && $ventasUltimoAno <= 3000000) {
                $tier = 2;
            } elseif ($ventasUltimoAno > 3000000) {
                $tier = 3;
            }

            // Actualizar el campo tier
            $cliente->update(['tier' => $tier]);
        } catch (\Exception $e) {
            Log::error('Error en CrearClientesJob: ' . $e->getMessage());
        }
    }
}
