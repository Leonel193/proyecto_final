<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hoy = now()->toDateString();
        $en30Dias = now()->addDays(30)->toDateString();

        $sumas = [
            'lotes as stock_disponible' => fn ($query) => $query
                ->whereDate('fecha_vencimiento', '>=', $hoy),

            'lotes as unidades_por_vencer' => fn ($query) => $query
                ->whereDate('fecha_vencimiento', '>=', $hoy)
                ->whereDate('fecha_vencimiento', '<=', $en30Dias),

            'lotes as unidades_vencidas' => fn ($query) => $query
                ->whereDate('fecha_vencimiento', '<', $hoy),
        ];

        $productos = Producto::withSum($sumas, 'cantidad')
            ->orderBy('nombre')
            ->get();

        $productosAlerta = Producto::where('estado', true)
            ->withSum($sumas, 'cantidad')
            ->get();

        $alertasDisponibles = $productosAlerta->filter(function ($producto) {
            return ($producto->stock_disponible ?? 0) > 0;
        })->count();

        $alertasStockBajo = $productosAlerta->filter(function ($producto) {
            return ($producto->stock_disponible ?? 0) <= $producto->stock_minimo;
        })->count();

        $alertasPorVencer = $productosAlerta->filter(function ($producto) {
            return ($producto->unidades_por_vencer ?? 0) > 0;
        })->count();

        $alertasVencidos = $productosAlerta->filter(function ($producto) {
            return ($producto->unidades_vencidas ?? 0) > 0;
        })->count();

        $alertasSinPrecio = $productosAlerta->filter(function ($producto) {
            return $producto->precio_venta <= 0;
        })->count();

        return view('productos.index', compact(
            'productos',
            'alertasDisponibles',
            'alertasStockBajo',
            'alertasPorVencer',
            'alertasVencidos',
            'alertasSinPrecio'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:150',
            'presentacion' => 'required|string|max:100',
            'marca' => 'nullable|string|max:100',
            'forma_venta' => ['required', Rule::in(Producto::FORMAS_VENTA)],
            'principio_activo' => 'nullable|string|max:150',
            'codigo_barra' => 'nullable|string|max:100|unique:productos,codigo_barra',
            'stock_minimo' => 'required|integer|min:0|max:100000',
            'categoria' => 'required|string|max:100',
            'estado' => 'required|boolean',
        ]);

        \App\Models\Producto::create($datos);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:150',
            'presentacion' => 'required|string|max:100',
            'marca' => 'nullable|string|max:100',
            'forma_venta' => ['required', Rule::in(Producto::FORMAS_VENTA)],
            'principio_activo' => 'nullable|string|max:150',
            'codigo_barra' => 'nullable|string|max:100|unique:productos,codigo_barra,' . $producto->id,
            'precio_venta'=> 'required|numeric|min:0|max:99999999.99',
            'stock_minimo' => 'required|integer|min:0|max:100000',
            'categoria' => 'required|string|max:100',
            'estado' => 'required|boolean',
        ]);
        $producto->update($datos);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    
public function destroy(Producto $producto)
{
    $tieneCompras = $producto->detallesCompra()->exists();

    // Verificar si el producto tiene ventas registradas
    $tieneVentas = \App\Models\DetalleVenta::whereHas('lote', function ($consulta) use ($producto) {
        $consulta->where('producto_id', $producto->id);
    })->exists();

    // Impedir la eliminación si tiene movimientos
    if ($tieneCompras || $tieneVentas) {
        return redirect()
            ->route('productos.index')
            ->with('error', 'No se puede eliminar este producto porque tiene compras o ventas registradas. Puedes marcarlo como inactivo.');
    }

    // Eliminar si no tiene movimientos relacionados
    $producto->delete();

    return redirect()
        ->route('productos.index')
        ->with('success', 'Producto eliminado correctamente.');
}

}
