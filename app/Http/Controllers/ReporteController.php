<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
    public function compras(Request $request)
    {   
        [$desde, $hasta] = $this->rangoFechas($request);

        $compras = Compra::with(['proveedor', 'usuario'])
            ->WhereDate('fecha_compra', '>=', $desde)
            ->WhereDate('fecha_compra', '<=', $hasta)
            ->orderByDesc('fecha_compra')
            ->orderByDesc('id')
            ->get();

        return view('reportes.compras', compact('compras', 'desde', 'hasta'));
    }

    /**
     * Ventas: solo las completadas, para que los totales no incluyan anulaciones.
     * Las anuladas se resumen aparte por si la vista quiere mostrarlas.
     */
    public function ventas(Request $request)
    {
        [$desde, $hasta] = $this->rangoFechas($request);

        $ventas = Venta::completadas()
            ->with(['usuario', 'detalles'])
            ->withCount('detalles')
            ->whereDate('fecha_venta', '>=', $desde)
            ->whereDate('fecha_venta', '<=', $hasta)
            ->orderByDesc('fecha_venta')
            ->orderByDesc('id')
            ->get();

        $totalVentas = $ventas->sum('total');

        $ventasAnuladas = Venta::where('estado', 'anulada')
            ->whereDate('fecha_venta', '>=', $desde)
            ->whereDate('fecha_venta', '<=', $hasta)
            ->count();

        return view('reportes.ventas', compact(
            'ventas', 'totalVentas', 'ventasAnuladas', 'desde', 'hasta'
        ));
    }

    private function rangoFechas(Request $request): array
    {
        $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $hasta = $request->input('hasta') ?: now()->toDateString();
        $desde = $request->input('desde') ?: Carbon::parse($hasta)->startOfMonth()->toDateString();
        
        return [$desde, $hasta];
    }
}