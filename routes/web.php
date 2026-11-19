<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
    
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
->name('login.authenticate')
->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function (){

    Route::get('/reportes/inventario', [ReporteController::class, 'inventario'])
    ->name('reportes.inventario');

    Route::get('/reportes/compras', [ReporteController::class, 'compras'])
    ->name('reportes.compras');

    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])
    ->name('reportes.ventas');
    
    Route::resource('productos', ProductoController::class)
        ->except(['index','show'])
        ->middleware('propietaria');
    Route::resource('productos', ProductoController::class)
    ->only(['index','show']);
    

    Route::resource('proveedores', ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor'])
        ->middleware('propietaria');
    
    Route::resource('compras', CompraController::class)
        ->only(['create', 'store'])
        ->middleware('propietaria');
    Route::resource('compras', CompraController::class)
        ->only (['index','show']);
    

    Route::resource('ventas', VentaController::class)
        ->only (['index', 'create','store','show']);
    Route::post('/ventas/{venta}/anular', [VentaController::class, 'anular'])
        ->name('ventas.anular');

    Route::middleware('propietaria')->group(function () {
        Route::resource('usuarios', UsuarioController::class)
            ->only(['index', 'create', 'store', 'edit', 'update']);

        Route::put('/usuarios/{usuario}/password', [UsuarioController::class, 'cambiarPassword'])
            ->name('usuarios.password');

        Route::patch('/usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])
            ->name('usuarios.estado');
    });
    
});