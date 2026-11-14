<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;

class DashboardController extends Controller
{
    public function index()
    {
        $productosAlerta = Producto::where('estado', true)
            ->withSum([
                'lotes as stock_disponible' => function ($query) {
                    $query->whereDate('fecha_vencimiento', '>=', now()->toDateString());
                }
            ], 'cantidad')
            ->withSum([
                'lotes as unidades_por_vencer' => function ($query) {
                    $query->whereDate('fecha_vencimiento', '>=', now()->toDateString())
                        ->whereDate('fecha_vencimiento', '<=', now()->addDays(30)->toDateString());
                }
            ], 'cantidad')
            ->withSum([
                'lotes as unidades_vencidas' => function ($query) {
                    $query->whereDate('fecha_vencimiento', '<', now()->toDateString());
                }
            ], 'cantidad')
            ->get();

        $totalProductos = $productosAlerta->count();

        $alertasStockBajo = $productosAlerta->filter(function ($producto) {
            return ($producto->stock_disponible ?? 0) <= $producto->stock_minimo;
        })->count();

        $alertasPorVencer = $productosAlerta->filter(function ($producto) {
            return ($producto->unidades_por_vencer ?? 0) > 0;
        })->count();

        $alertasVencidos = $productosAlerta->filter(function ($producto) {
            return ($producto->unidades_vencidas ?? 0) > 0;
        })->count();

        $ventasHoy = Venta::whereDate('fecha_venta', today())->count();

        return view('dashboard', compact(
            'totalProductos',
            'alertasStockBajo',
            'alertasPorVencer',
            'alertasVencidos',
            'ventasHoy'
        ));
    }
}