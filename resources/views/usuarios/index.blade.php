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

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table_hover table-bordered align-middle mb-0">
                    <thead class="table-dark"
                    style="--bs-table-bg: #2c3e50; --bs-table-color: #ffffff;">
                        <tr>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr class="{{$usuario->activo ? '' : 'text-muted'}}">
                                <td>{{ $usuario->nombres }} {{ $usuario->apellidos }}
                                    @if($usuario->id === auth()->id())
                                        <span class="badge text-bg-info">Tú</span>
                                    @endif
                                </td>
                                <td>{{ $usuario->usuario }}</td>
                                <td>{{ $usuario->correo }}</td>
                                <td>{{ $usuario->telefono ?? 'No registrado' }}</td>
                                <td>{{ ucfirst($usuario->rol) }}</td>
                                <td>
                                    @if($usuario->activo)
                                        <span class="badge text-bg-success">Activo</span>
                                    @else
                                        <span class="badge text-bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('usuarios.edit', $usuario) }}"
                                            class="btn btn-warning btn-sm">
                                                Editar
                                            </a>

                                            @if($usuario->id !== auth()->id())
                                                <form action="{{ route('usuarios.estado', $usuario) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-sm {{ $usuario->activo ? 'btn-danger' : 'btn-success' }}"
                                                            onclick="return confirm('{{ $usuario->activo ? 'Desactivar' : 'Activar' }} este usuario?')">
                                                        {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        No hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection                        