<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Venta;

class ReporteController extends Controller
{
    /**
     * Inventario: existencias vigentes, por vencer (próximos 30 días) y vencidas.
     */
    public function inventario()
    {
        $hoy = now()->toDateString();
        $limite = now()->addDays(30)->toDateString();

        $productos = Producto::where('estado', true)
            ->withSum([
                'lotes as stock_disponible' => function ($query) use ($hoy) {
                    $query->whereDate('fecha_vencimiento', '>=', $hoy);
                },
            ], 'cantidad')
            ->withSum([
                'lotes as unidades_por_vencer' => function ($query) use ($hoy, $limite) {
                    $query->whereDate('fecha_vencimiento', '>=', $hoy)
                        ->whereDate('fecha_vencimiento', '<=', $limite);
                },
            ], 'cantidad')
            ->withSum([
                'lotes as unidades_vencidas' => function ($query) use ($hoy) {
                    $query->whereDate('fecha_vencimiento', '<', $hoy);
                },
            ], 'cantidad')
            ->orderBy('nombre')
            ->get()
            ->each(function ($producto) {
                $producto->stock_disponible = (int) $producto->stock_disponible;
                $producto->unidades_por_vencer = (int) $producto->unidades_por_vencer;
                $producto->unidades_vencidas = (int) $producto->unidades_vencidas;

                $producto->bajo_stock = $producto->stock_disponible <= $producto->stock_minimo;
            });

        return view('reportes.inventario', compact('productos'));
    }

    /**
     * Historial de compras.
     */
    public function compras()
    {
        $compras = Compra::with(['proveedor', 'usuario'])
            ->orderByDesc('fecha_compra')
            ->orderByDesc('id')
            ->get();

        return view('reportes.compras', compact('compras'));
    }

    /**
     * Ventas: solo las completadas, para que los totales no incluyan anulaciones.
     * Las anuladas se resumen aparte por si la vista quiere mostrarlas.
     */
    public function ventas()
    {
        $ventas = Venta::completadas()
            ->with(['usuario', 'detalles'])
            ->withCount('detalles')
            ->orderByDesc('fecha_venta')
            ->orderByDesc('id')
            ->get();

        $totalVentas = $ventas->sum('total');

        $ventasAnuladas = Venta::where('estado', 'anulada')->count();

        return view('reportes.ventas', compact('ventas', 'totalVentas', 'ventasAnuladas'));
    }
}