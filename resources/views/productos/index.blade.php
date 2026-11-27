@extends('layouts.app')
@section('content')

<style>
    .filtro-card .card {
        transition: transform .1s ease, box-shadow .1s ease;
    }

    .filtro-card:hover .card {
        transform: translateY(-2px);
    }

    .filtro-card.filtro-activo .card {
        box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .35) !important;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h2 class="mb-0">Inventario</h2>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('reportes.inventario') }}" class="btn btn-secondary">
            Reporte de inventario
        </a>

        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            Nuevo Producto
        </a>
    </div>
</div>

{{-- Tarjetas que funcionan como filtros --}}
<div class="row g-3 mb-1">

    <div class="col-6 col-md-4 col-xl">
        <button type="button"
                class="filtro-card w-100 h-100 p-0 border-0 bg-transparent text-start"
                data-filtro="disponible"
                aria-pressed="false">
            <div class="card shadow-sm border-success h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Disponibles</h6>
                        <h3 class="text-success mb-0">{{ $alertasDisponibles }}</h3>
                        <small class="text-muted">Productos</small>
                    </div>
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                </div>
            </div>
        </button>
    </div>

    <div class="col-6 col-md-4 col-xl">
        <button type="button"
                class="filtro-card w-100 h-100 p-0 border-0 bg-transparent text-start"
                data-filtro="stock_bajo"
                aria-pressed="false">
            <div class="card shadow-sm border-danger h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Stock bajo</h6>
                        <h3 class="text-danger mb-0">{{ $alertasStockBajo }}</h3>
                        <small class="text-muted">Productos</small>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                </div>
            </div>
        </button>
    </div>

    <div class="col-6 col-md-4 col-xl">
        <button type="button"
                class="filtro-card w-100 h-100 p-0 border-0 bg-transparent text-start"
                data-filtro="por_vencer"
                aria-pressed="false">
            <div class="card shadow-sm border-warning h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Próximos a vencer</h6>
                        <h3 class="text-warning mb-0">{{ $alertasPorVencer }}</h3>
                        <small class="text-muted">Productos</small>
                    </div>
                    <i class="bi bi-calendar-event-fill text-warning fs-1"></i>
                </div>
            </div>
        </button>
    </div>

    <div class="col-6 col-md-4 col-xl">
        <button type="button"
                class="filtro-card w-100 h-100 p-0 border-0 bg-transparent text-start"
                data-filtro="vencido"
                aria-pressed="false">
            <div class="card shadow-sm border-danger h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2">Productos vencidos</h6>
                        <h3 class="text-danger mb-0">{{ $alertasVencidos }}</h3>
                        <small class="text-muted">Productos</small>
                    </div>
                    <i class="bi bi-calendar-x-fill text-danger fs-1"></i>
                </div>
            </div>
        </button>
    </div>

    {{-- Solo la propietaria asigna precios, por eso solo ella ve esta tarjeta --}}
    @if (auth()->user()->rol === 'propietaria')
        <div class="col-6 col-md-4 col-xl">
            <button type="button"
                    class="filtro-card w-100 h-100 p-0 border-0 bg-transparent text-start"
                    data-filtro="sin_precio"
                    aria-pressed="false">
                <div class="card shadow-sm border-secondary h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Sin precio de venta</h6>
                            <h3 class="text-secondary mb-0">{{ $alertasSinPrecio }}</h3>
                            <small class="text-muted">Productos</small>
                        </div>
                        <i class="bi bi-tag-fill text-secondary fs-1"></i>
                    </div>
                </div>
            </button>
        </div>
    @endif

</div>

<div class="row mb-4">
    <div class="col-md-8 col-lg-6">
        <label for="buscar" class="form-label">Buscar producto</label>
        <div class="input-group">
            <input
                type="text"
                id="buscar"
                class="form-control"
                placeholder="Buscar"
                autocomplete="off">
            <button type="button" id="btn-limpiar" class="btn btn-secondary">
                Limpiar
            </button>
        </div>
    </div>
</div>

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

<div class="card shadow">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark" style="--bs-table-bg: #2c3e50; --bs-table-color: #ffffff;">
                    <tr>
                        <th>Producto</th>
                        <th class="d-none d-lg-table-cell">Presentación</th>
                        <th class="text-nowrap">Precio de venta</th>
                        <th>Existencias</th>
                        <th>Por vencer</th>
                        <th>Estado</th>
                        <th class="text-nowrap">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($productos as $producto)
                        @php
                            $existencias = $producto->stock_disponible ?? 0;
                            $porVencer = $producto->unidades_por_vencer ?? 0;
                            $vencidas = $producto->unidades_vencidas ?? 0;

                            $proximoAVencer = $porVencer > 0;
                            $stockBajo = $existencias <= $producto->stock_minimo;
                            $disponible = $existencias > 0;
                            $sinPrecio = $producto->precio_venta <= 0;

                            // Unidades vigentes que no están por vencer
                            $buenas = max($existencias - $porVencer, 0);

                            // Estado principal: el que tenga más unidades (en empate gana el más grave)
                            if ($existencias == 0 && $vencidas == 0) {
                                $estadoClave = 'sin_stock';
                            } elseif ($vencidas > 0 && $vencidas >= $buenas && $vencidas >= $porVencer) {
                                $estadoClave = 'vencido';
                            } elseif ($porVencer > 0 && $porVencer >= $buenas) {
                                $estadoClave = 'por_vencer';
                            } else {
                                $estadoClave = $stockBajo ? 'stock_bajo': 'disponible';
                            }

                            $estados = [
                                'disponible' => ['Disponible', 'success'],
                                'stock_bajo' => ['Stock bajo', 'danger'],
                                'por_vencer' => ['Próximo a vencer', 'warning'],
                                'vencido'    => ['Vencido', 'danger'],
                                'sin_stock'  => ['Sin existencias', 'secondary'],
                            ];

                            [$estadoTexto, $estadoColor] = $estados[$estadoClave];

                            $textoBusqueda = implode(' ', array_filter([
                                $producto->nombre,
                                $producto->presentacion,
                                $producto->marca,
                                $producto->principio_activo,
                                $producto->codigo_barra,
                                $producto->categoria,
                                $estadoTexto,
                                $proximoAVencer ? 'próximo a vencer' : null,
                                $vencidas > 0 ? 'con vencidos' : null,
                                $sinPrecio ? 'sin precio' : null,
                            ]));
                        @endphp

                        {{-- Las banderas de filtro cuentan solo productos activos, igual que las tarjetas --}}
                        <tr class="fila-producto"
                            data-buscar="{{ $textoBusqueda }}"
                            data-disponible="{{ $producto->estado && $disponible ? 1 : 0 }}"
                            data-stock-bajo="{{ $producto->estado && $stockBajo ? 1 : 0 }}"
                            data-por-vencer="{{ $producto->estado && $porVencer > 0 ? 1 : 0 }}"
                            data-vencido="{{ $producto->estado && $vencidas > 0 ? 1 : 0 }}"
                            data-sin-precio="{{ $producto->estado && $sinPrecio ? 1 : 0 }}">

                            <td>
                                {{ $producto->nombre }}
                                {{-- La presentación se ve debajo del nombre cuando su columna está oculta --}}
                                <small class="text-muted d-block d-lg-none">
                                    {{ $producto->presentacion }}
                                </small>
                            </td>

                            <td class="d-none d-lg-table-cell">{{ $producto->presentacion }}</td>

                            <td class="text-nowrap">
                                @if ($sinPrecio)
                                    <span class="badge text-bg-secondary">Sin precio</span>
                                @else
                                    Q {{ number_format($producto->precio_venta, 2) }}
                                @endif
                            </td>

                            <td class="text-nowrap">
                                <strong>{{ $existencias }}</strong>
                                unidades
                            </td>

                            <td class="text-nowrap">
                                @if ($porVencer > 0)
                                    <span class="badge text-bg-warning">
                                        {{ $porVencer }} unidades
                                    </span>
                                @else
                                    <span class="text-muted">0 unidades</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge text-bg-{{ $estadoColor }}">{{ $estadoTexto }}</span>
                            </td>

                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('productos.show', $producto->id) }}"
                                       class="btn btn-info btn-sm">
                                        Ver
                                    </a>

                                    <a href="{{ route('productos.edit', $producto->id) }}"
                                       class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('productos.destroy', $producto->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Desea eliminar este producto?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay productos registrados.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Se muestra cuando la búsqueda o el filtro no encuentran nada --}}
                    <tr id="sin-resultados" class="d-none">
                        <td colspan="7" class="text-center text-muted py-4">
                            No se encontraron productos con ese filtro o búsqueda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const buscar = document.getElementById('buscar');
    const btnLimpiar = document.getElementById('btn-limpiar');
    const filas = document.querySelectorAll('.fila-producto');
    const sinResultados = document.getElementById('sin-resultados');
    const botonesFiltro = document.querySelectorAll('[data-filtro]');

    // Filtro activo: null, 'disponible', 'stock_bajo', 'por_vencer', 'vencido' o 'sin_precio'
    let filtroActivo = null;

    // Qué atributo de la fila corresponde a cada filtro
    const atributoPorFiltro = {
        disponible: 'disponible',
        stock_bajo: 'stockBajo',
        por_vencer: 'porVencer',
        vencido: 'vencido',
        sin_precio: 'sinPrecio',
    };

    // Ignora mayúsculas y tildes: "analgesico" encuentra "Analgésico"
    const normalizar = (texto) => texto
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();

    function actualizarBotones() {
        botonesFiltro.forEach(boton => {
            const activo = boton.dataset.filtro === filtroActivo;

            boton.classList.toggle('filtro-activo', activo);
            boton.setAttribute('aria-pressed', activo ? 'true' : 'false');
        });
    }

    function filtrar() {
        const q = normalizar(buscar.value.trim());
        let visibles = 0;

        filas.forEach(fila => {
            const coincideTexto = normalizar(fila.dataset.buscar).includes(q);

            const coincideFiltro = !filtroActivo
                || fila.dataset[atributoPorFiltro[filtroActivo]] === '1';

            const mostrar = coincideTexto && coincideFiltro;

            fila.classList.toggle('d-none', !mostrar);

            if (mostrar) {
                visibles++;
            }
        });

        // Solo mostrar el mensaje si hay productos y ninguno coincide
        sinResultados.classList.toggle('d-none', filas.length === 0 || visibles > 0);

        actualizarBotones();
    }

    buscar.addEventListener('input', filtrar);

    // Clic en una tarjeta: activa el filtro o lo quita si ya estaba activo
    botonesFiltro.forEach(boton => {
        boton.addEventListener('click', () => {
            filtroActivo = (filtroActivo === boton.dataset.filtro)
                ? null
                : boton.dataset.filtro;

            filtrar();
        });
    });

    btnLimpiar.addEventListener('click', () => {
        buscar.value = '';
        filtroActivo = null;
        filtrar();
        buscar.focus();
    });

    filtrar();
});
</script>

@endsection