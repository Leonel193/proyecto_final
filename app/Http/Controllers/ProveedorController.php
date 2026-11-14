<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $proveedores = Proveedor::when($buscar, function ($query, $buscar) {
            $query->where('nombre', 'like', '%' . $buscar . '%')
                ->orWhere('telefono', 'like', '%' . $buscar . '%')
                ->orWhere('tipo_producto', 'like', '%' . $buscar . '%');
        })
        ->orderBy('nombre')
        ->paginate(10)
        ->withQueryString();

        return view('proveedores.index', compact('proveedores', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'tipo_producto' => 'required|string|max:150',
            'fecha_visita' => 'nullable|date',
        ], [
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 150 caracteres.',
            'tipo_producto.required' => 'Debes indicar qué tipo de producto distribuye.',
            'fecha_visita.date' => 'La fecha de visita no es válida.',
        ]);

        Proveedor::create($datos);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
         $datos = $request->validate([
        'nombre' => 'required|string|max:150',
        'telefono' => 'nullable|string|max:20',
        'tipo_producto' => 'required|string|max:150',
        'fecha_visita' => 'nullable|date',
    ], 
    [
        'nombre.required' => 'El nombre del proveedor es obligatorio.',
        'nombre.max' => 'El nombre no debe superar los 150 caracteres.',
        'tipo_producto.required' => 'Debes indicar qué tipo de producto distribuye.',
        'fecha_visita.date' => 'La fecha de visita no es válida.',
    ]);

    $proveedor->update($datos);

    return redirect()
        ->route('proveedores.index')
        ->with('success', 'Proveedor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
         if ($proveedor->compras()->exists()) {
        return redirect()
            ->route('proveedores.index')
            ->with('error', 'No se puede eliminar este proveedor porque tiene compras registradas.');
    }

    $proveedor->delete();

    return redirect()
        ->route('proveedores.index')
        ->with('success', 'Proveedor eliminado correctamente.');
    }
}
