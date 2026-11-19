<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::orderByDesc('activo')
        ->orderBy('nombres')
        ->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'usuario' => 'required|string|max:50|unique:usuarios,usuario',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'required|string|email|max:150|unique:usuarios,correo',
            'rol' => ['required', Rule::in(['propietaria','vendedor'])],
            'password' => 'required|string|min:8|confirmed',
        ]);
        Usuario::create($datos);
        return redirect()->route('usuarios.index')
        ->with('success', 'Usuario creado correctamente.');
    }

    
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $datos = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'usuario' => [
                'required', 'string', 'max:50',
                Rule::unique('usuarios', 'usuario')->ignore($usuario->id),
            ],
            'telefono' => 'nullable|string|max:20',
            'correo' => [
                'required', 'string', 'email', 'max:150',
                Rule::unique('usuarios', 'correo')->ignore($usuario->id),
            ],
            'rol' => ['required', Rule::in(['propietaria', 'vendedor'])],
        ]);

        if ($usuario->id === $request->user()->id && $datos['rol'] !== 'propietaria') {
            return back()
                ->withInput()
                ->withErrors(['rol' => 'No puedes quitarte a ti misma el rol de propietaria.']);
        }
        $usuario->update($datos);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

     public function cambiarPassword(Request $request, Usuario $usuario)
    {
        $datos = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario->update(['password' => $datos['password']]);

        return redirect()
            ->route('usuarios.edit', $usuario)
            ->with('success', 'Contraseña actualizada correctamente.');
    }

    public function cambiarEstado(Request $request, Usuario $usuario)
    {
        if ($usuario->id === $request->user()->id) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $usuario->update(['activo' => !$usuario->activo]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', $usuario->activo
                ? 'Usuario activado correctamente.'
                : 'Usuario desactivado correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        //
    }
}
