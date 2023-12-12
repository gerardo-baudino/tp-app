<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('import_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('fecha');
            $table->char('tipo_comprobante', 1)->comment('A o B');
            $table->string('numero_punto_venta', 5);
            $table->string('numero_comprobante', 10);
            $table->double('importe_venta', 15, 2);
            $table->bigInteger('cuit_cliente');
            $table->string('razon_social', 30);
            $table->bigInteger('nro_cliente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_ventas');
    }
};
