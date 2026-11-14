<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    /**
     * Listado de ventas.
     */
    public function index()
    {
        $ventas = Venta::with('usuario')
            ->orderByDesc('fecha_venta')
            ->orderByDesc('id')
            ->paginate(15);

        return view('ventas.index', compact('ventas'));
    }

    /**
     * Formulario de nueva venta: una fila por producto con existencias vigentes.
     */
    public function create()
    {
        $vigentes = fn ($query) => $query
            ->whereDate('fecha_vencimiento', '>=', now()->toDateString())
            ->where('cantidad', '>', 0);

        $productos = Producto::where('estado', true)
            ->whereHas('lotes', $vigentes)
            ->withSum(['lotes as stock_vigente' => $vigentes], 'cantidad')
            ->with(['lotes' => fn ($query) => $vigentes($query)
                ->orderBy('fecha_vencimiento')
                ->orderBy('id')])
            ->orderBy('nombre')
            ->get();

        return view('ventas.create', compact('productos'));
    }

    /**
     * Registrar la venta. Por cada producto se vende primero el lote
     * que vence antes y, cuando se agota, se continúa con el siguiente.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'productos' => 'required|array',
            'productos.*' => 'nullable|integer|min:0',
        ]);

        $cantidades = collect($datos['productos'])
            ->map(fn ($cantidad) => (int) $cantidad)
            ->filter(fn ($cantidad) => $cantidad > 0)
            ->sortKeys();

        if ($cantidades->isEmpty()) {
            throw ValidationException::withMessages([
                'productos' => 'Debe seleccionar al menos un producto.',
            ]);
        }

        try {
            DB::transaction(function () use ($cantidades) {

                $venta = Venta::create([
                    'usuario_id' => Auth::id(),
                    'fecha_venta' => now()->toDateString(),
                    'total' => 0,
                ]);

                $total = 0;

                foreach ($cantidades as $productoId => $cantidadPedida) {

                    $producto = Producto::find($productoId);

                    if (!$producto) {
                        throw ValidationException::withMessages([
                            'productos' => 'Uno de los productos seleccionados no existe.',
                        ]);
                    }

                    if (!$producto->estado) {
                        throw ValidationException::withMessages([
                            'productos' => 'El producto ' . $producto->nombre . ' está inactivo.',
                        ]);
                    }

                    $lotes = Lote::where('producto_id', $producto->id)
                        ->where('cantidad', '>', 0)
                        ->whereDate('fecha_vencimiento', '>=', now()->toDateString())
                        ->orderBy('fecha_vencimiento')
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    if ($lotes->sum('cantidad') < $cantidadPedida) {
                        throw ValidationException::withMessages([
                            'productos' => 'Existencias insuficientes para ' . $producto->nombre
                                . ' (disponibles: ' . $lotes->sum('cantidad') . ').',
                        ]);
                    }

                    $pendiente = $cantidadPedida;

                    foreach ($lotes as $lote) {

                        if ($pendiente <= 0) {
                            break;
                        }

                        $tomar = min($lote->cantidad, $pendiente);
                        $precio = $producto->precio_venta;
                        $subtotal = round($precio * $tomar, 2);

                        DetalleVenta::create([
                            'venta_id' => $venta->id,
                            'lote_id' => $lote->id,
                            'cantidad' => $tomar,
                            'precio_unitario' => $precio,
                            'subtotal' => $subtotal,
                        ]);

                        $total += $subtotal;
                        $pendiente -= $tomar;
                    }
                }

                $venta->update(['total' => round($total, 2)]);
            });
        } catch (QueryException $e) {
            if (($e->errorInfo[0] ?? null) === '45000') {
                return back()
                    ->withInput()
                    ->withErrors(['productos' => 'No se pudo registrar la venta: stock insuficiente o producto vencido.']);
            }

            throw $e;
        }

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Detalle de una venta.
     */
    public function show(Venta $venta)
    {
        $venta->load([
            'usuario',
            'anuladaPor',
            'detalles.lote.producto',
        ]);

        return view('ventas.show', compact('venta'));
    }

    
    public function anular(Request $request, Venta $venta)
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        abort_unless($usuario->puedeAnularVenta($venta), 403);

        $datos = $request->validate([
            'motivo_anulacion' => 'required|string|min:5|max:255',
        ]);

        DB::transaction(function () use ($venta, $datos) {

            $ventaBloqueada = Venta::lockForUpdate()->findOrFail($venta->id);

            if ($ventaBloqueada->estado === 'anulada') {
                throw ValidationException::withMessages([
                    'motivo_anulacion' => 'Esta venta ya fue anulada.',
                ]);
            }

            $detalles = $ventaBloqueada->detalles()->orderBy('lote_id')->get();

            foreach ($detalles as $detalle) {
                Lote::whereKey($detalle->lote_id)
                    ->increment('cantidad', $detalle->cantidad);
            }

            $ventaBloqueada->update([
                'estado' => 'anulada',
                'motivo_anulacion' => $datos['motivo_anulacion'],
                'anulada_por' => Auth::id(),
                'anulada_at' => now(),
            ]);
        });

        return back()->with('success', 'Venta anulada correctamente.');
    }
}