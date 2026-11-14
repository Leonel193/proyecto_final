
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Proveedores</h2>

    <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>
        Nuevo Proveedor
    </a>
</div>

{{-- Búsqueda de proveedores --}}
<form action="{{ route('proveedores.index') }}" method="GET" class="mb-4">
    <div class="row g-3 align-items-end">

        <div class="col-md-5">
            <label for="buscar" class="form-label">
                Buscar proveedor
            </label>

            <input
                type="text"
                name="buscar"
                id="buscar"
                class="form-control"
                placeholder="Nombre, teléfono o tipo de producto"
                value="{{ $buscar }}"
            >
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search me-1"></i>
                Buscar
            </button>

            <a href="{{ route('proveedores.index') }}"
               class="btn btn-outline-secondary">
                Limpiar
            </a>
        </div>

    </div>
</form>

{{-- Mensajes del sistema --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Tabla de proveedores --}}
<div class="card shadow">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">

                <thead class="table-dark"
                    style="--bs-table-bg: #2c3e50; --bs-table-color: #ffffff;">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Tipo de producto</th>
                        <th>Fecha de visita</th>
                        <th width="200">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($proveedores as $proveedor)
                        <tr>
                            <td>{{ $proveedor->id }}</td>
                            <td>{{ $proveedor->nombre }}</td>
                            <td>{{ $proveedor->telefono ?: 'No registrado' }}</td>
                            <td>{{ $proveedor->tipo_producto }}</td>

                            <td>
                                {{ $proveedor->fecha_visita
                                    ? \Carbon\Carbon::parse($proveedor->fecha_visita)->format('d/m/Y')
                                    : 'No registrada' }}
                            </td>

                            <td>
                                <a href="{{ route('proveedores.show', $proveedor->id) }}"
                                   class="btn btn-info btn-sm">
                                    Ver
                                </a>

                                <a href="{{ route('proveedores.edit', $proveedor->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <form action="{{ route('proveedores.destroy', $proveedor->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Desea eliminar este proveedor?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay proveedores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $proveedores->links() }}
        </div>

    </div>
</div>

@endsection
