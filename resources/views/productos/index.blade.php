@extends('layouts.app')
@section('content')


<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inventario</h2>
    <a href="{{ route('productos.create') }}"class="btn btn-primary">
        Nuevo Producto
    </a>
</div>


{{-- Búsqueda y filtro de inventario --}}
<form action="{{ route('productos.index') }}" method="GET" class="mb-4">
    <div class="row g-3 align-items-end">

        <div class="col-md-5">
            <label for="buscar" class="form-label">
                Buscar producto
            </label>

            <input
                type="text"
                name="buscar"
                id="buscar"
                class="form-control"
                placeholder="Nombre, marca o código de barras"
                value="{{ request('buscar') }}"
            >
        </div>

        <div class="col-md-4">
            <label for="estado_inventario" class="form-label">
                Estado del inventario
            </label>

            <select
                name="estado_inventario"
                id="estado_inventario"
                class="form-select"
            >
                <option value=""
                    {{ request('estado_inventario') === '' ? 'selected' : '' }}>
                    Todos los productos
                </option>

                <option value="disponible"
                    {{ request('estado_inventario') === 'disponible' ? 'selected' : '' }}>
                    Disponible
                </option>

                <option value="stock_bajo"
                    {{ request('estado_inventario') === 'stock_bajo' ? 'selected' : '' }}>
                    Stock bajo
                </option>

                <option value="proximo_vencer"
                    {{ request('estado_inventario') === 'proximo_vencer' ? 'selected' : '' }}>
                    Próximo a vencer
                </option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search me-1"></i>
                Buscar
            </button>

            <a href="{{ route('productos.index') }}"
               class="btn btn-outline-secondary">
                Limpiar
            </a>
        </div>

    </div>
</form>



    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
    <   div class="alert alert-danger">
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

<div class="card shadow">
    <div class="card-body">

        <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark" style="--bs-table-bg: #2c3e50; --bs-table-color: #ffffff;">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Presentación</th>
                        <th>Precio de venta</th>
                        <th>Stock mínimo</th>
                        <th>Estado</th>
                        <th width="200">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                        @forelse ($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->presentacion }}</td>

                            <td>
                                Q {{ number_format($producto->precio_venta, 2) }}
                            </td>

                            <td>{{ $producto->stock_minimo }}</td>

                            
                            <td>
                                @php
                                    $existencias = $producto->lotes()
                                        ->whereDate('fecha_vencimiento', '>=', now()->toDateString())
                                        ->sum('cantidad');

                                    $proximoAVencer = $producto->lotes()
                                        ->whereDate('fecha_vencimiento', '>=', now()->toDateString())
                                        ->whereDate('fecha_vencimiento', '<=', now()->addDays(30)->toDateString())
                                        ->exists();
                                @endphp

                                @if ($proximoAVencer)
                                    <span class="badge text-bg-warning">Próximo a vencer</span>
                                @elseif ($existencias <= $producto->stock_minimo)
                                    <span class="badge text-bg-danger">Stock bajo</span>
                                @else
                                    <span class="badge text-bg-success">Disponible</span>
                                @endif
                            </td>

                            <td>
                                    <a href="{{ route('productos.show', $producto->id) }}"
                                        class="btn btn-info btn-sm">
                                        Ver
                                    </a>

                                    <a href="{{ route('productos.edit', $producto->id) }}"
                                        class="btn btn-warning btn-sm">
                                         Editar
                                    </a>

                                    <form action="{{ route('productos.destroy', $producto->id) }}"
                                        method="POST"
                                        class="d-inline">
                                        
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Desea eliminar este producto?')">
                                            Eliminar
                                        </button>      
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay productos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
        </table>
    </div>
</div>
@endsection
