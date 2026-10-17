<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $consulta = Producto::query();

         if ($request->filled('buscar')) {
            $termino = $request->buscar;

            $consulta->where(function ($query) use ($termino) {
                $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('marca', 'like', "%{$termino}%")
                    ->orWhere('principio_activo', 'like', "%{$termino}%");
            });
        }   

         if ($request->estado_inventario === 'disponible') {
        $consulta->where('estado', true)
            ->whereRaw('
                (
                    SELECT COALESCE(SUM(l.cantidad), 0)
                    FROM lotes l
                    WHERE l.producto_id = productos.id
                      AND l.fecha_vencimiento >= ?
                ) > productos.stock_minimo
            ', [now()->toDateString()]);
    }

    if ($request->estado_inventario === 'stock_bajo') {
        $consulta->where('estado', true)
            ->whereRaw('
                (
                    SELECT COALESCE(SUM(l.cantidad), 0)
                    FROM lotes l
                    WHERE l.producto_id = productos.id
                      AND l.fecha_vencimiento >= ?
                ) <= productos.stock_minimo
            ', [now()->toDateString()]);
    }
        if ($request->estado_inventario === 'proximo_vencer') {
        $consulta->whereHas('lotes', function ($query) {
            $query->whereDate(
                'fecha_vencimiento',
                '>=',
                now()->toDateString()
            )->whereDate(
                'fecha_vencimiento',
                '<=',
                now()->addDays(30)->toDateString()
            );
        });
    }
        $productos = $consulta->get();
        
        // Obtener lotes vencidos o próximos a vencer
        $lotesPorVencer = \App\Models\Lote::with('producto')
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(30)->toDateString())
            ->orderBy('fecha_vencimiento')
            ->get();

        return view('productos.index', compact(
            'productos',
            'lotesPorVencer'
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
            'nombre' => 'required|string|max:255',
            'presentacion' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'forma_venta' => 'nullable|string|max:100',
            'principio_activo' => 'nullable|string|max:255',
            'codigo_barra' => 'nullable|string|max:100|unique:productos,codigo_barra',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:255',
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
            'nombre' => 'required|string|max:255',
            'presentacion' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'forma_venta' => 'nullable|string|max:100',
            'principio_activo' => 'nullable|string|max:255',
            'codigo_barra' => 'nullable|string|max:100|unique:productos,codigo_barra,' . $producto->id,
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:255',
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
