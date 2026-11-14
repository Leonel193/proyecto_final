<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Lote;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with(['proveedor', 'usuario'])
            ->orderBy('fecha_compra', 'desc')
            ->paginate(10);

        return view('compras.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            $proveedores = Proveedor::orderBy('nombre')->get();
            $productos = Producto::where('estado', true)
            ->orderBy('nombre')
            ->get();

            $usuarios = Usuario::orderBy('nombres')->get();

            return view('compras.create', compact(
                'proveedores', 
                'productos', 
                'usuarios'
                ));
        }

        /**
         * Store a newly created resource in storage.
         */
    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date|before_or_equal:today',

            'productos' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $productos, $fail) {
                    $vistos = [];

                    foreach ($productos as $p) {
                        $numeroLote = trim($p['numero_lote'] ?? '');

                        if ($numeroLote === '') {
                            continue;
                        }

                        $productoId = $p['producto_id'] ?? 0;
                        $clave = $productoId . '-' . strtolower($numeroLote);

                        if (in_array($clave, $vistos, true)) {
                            $fail('No puedes repetir el mismo número de lote para un producto dentro de la compra.');
                            return;
                        }
                        $vistos[] = $clave;

                        $existe = Lote::where('producto_id', $productoId)
                            ->where('numero_lote', $numeroLote)
                            ->exists();

                        if ($existe) {
                            $fail("El lote {$numeroLote} ya está registrado para ese producto.");
                            return;
                        }
                    }
                },
            ],

            'productos.*.producto_id' => [
                'required',
                Rule::exists('productos', 'id')->where('estado', true),
            ],
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0.01',
            'productos.*.numero_lote' => 'nullable|string|max:100',
            'productos.*.fecha_vencimiento' => 'required|date|after:today',
        ]);

        DB::transaction(function () use ($request) {

            $lineas = [];
            $total = 0;

            foreach ($request->productos as $p) {
                $p['subtotal'] = round($p['cantidad'] * $p['precio_unitario'], 2);
                $total += $p['subtotal'];
                $lineas[] = $p;
            }

            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,
                'usuario_id' => Auth::id(),
                'fecha_compra' => $request->fecha_compra,
                'total' => round($total, 2),
            ]);

            foreach ($lineas as $p) {
                $detalle = $compra->detalles()->create([
                    'producto_id' => $p['producto_id'],
                    'cantidad' => $p['cantidad'],
                    'precio_unitario' => $p['precio_unitario'],
                    'subtotal' => $p['subtotal'],
                ]);

                $detalle->lotes()->create([
                    'producto_id' => $p['producto_id'],
                    'numero_lote' => $p['numero_lote'] ?? null,
                    'fecha_vencimiento' => $p['fecha_vencimiento'],
                    'fecha_ingreso' => $request->fecha_compra,
                    'cantidad' => $p['cantidad'],
                ]);
            }
        });

        return redirect()
            ->route('compras.index')
            ->with('success', 'Compra registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'usuario', 'detalles.producto', 'detalles.lotes']);

        return view('compras.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        //
    }
}
