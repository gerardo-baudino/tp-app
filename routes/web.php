<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ClienteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas para la importación
Route::get('/importar-ventas', [ImportController::class, 'mostrarFormulario'])->name('import.form');
Route::post('/importar-ventas', [ImportController::class, 'importarVentas'])->name('importar.ventas');

// Rutas para la creación/modificación manual de clientes
Route::get('/clientes', [ClienteController::class, 'mostrarFormulario'])->name('cliente.form');
Route::get('/clientes', [ClienteController::class, 'todos'])->name('cliente.todos');
Route::get('/clientes/buscar', [ClienteController::class, 'buscarPorCuit'])->name('cliente.buscar');
Route::put('/clientes/actualizar/{id}', [ClienteController::class, 'actualizar'])->name('cliente.actualizar');
Route::post('/clientes/crear', [ClienteController::class, 'crear'])->name('cliente.crear');
