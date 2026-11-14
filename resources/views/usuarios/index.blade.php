@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Usuarios registrados</h2>

        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            Nuevo usuario
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->nombres }} {{ $usuario->apellidos }}</td>
                            <td>{{ $usuario->usuario }}</td>
                            <td>{{ $usuario->correo }}</td>
                            <td>{{ $usuario->telefono ?? 'No registrado' }}</td>
                            <td>{{ ucfirst($usuario->rol) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection