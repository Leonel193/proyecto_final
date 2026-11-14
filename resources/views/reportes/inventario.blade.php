@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Reporte de inventario</h3>
            <p class="text-muted mb-0">
                Existencias y estado actual de los productos.
            </p>
        </div>

        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i>
            Imprimir / Guardar PDF
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="text-center mb-4">
                <h4>Farmacia Nazareth</h4>
                <h5>Reporte de inventario</h5>
                <p class="text-muted">
                    Fecha de generación: {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Presentación</th>
                            <th>Precio de venta</th>
                            <th>Existencias</th>
                            <th>Por vencer</th>
                            <th>Vencidos</th>
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

                                <td>
                                    {{ $producto->stock_disponible ?? 0 }}
                                </td>

                                <td>
                                    {{ $producto->unidades_por_vencer ?? 0 }}
                                </td>

                                <td>
                                    {{ $producto->unidades_vencidas ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay productos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="text-end mt-3">
                <strong>Total de productos: {{ $productos->count() }}</strong>
            </div>

        </div>
    </div>

</div>

<style>
   @media print {

    @page {
        size: landscape;
        margin: 10mm;
    }

    .topbar,
    .sidebar,
    nav,
    button,
    .btn {
        display: none !important;
    }

    .main-content {
        margin: 0 !important;
        padding: 0 !important;
        min-height: 0 !important;
    }

    html, body {
        height: auto !important;
        min-height: 0 !important;
        background: white !important;
        font-size: 11px;
    }

    .container-fluid {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-body {
        padding: 5px !important;
    }

    table {
        width: 100% !important;
        font-size: 10px;
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
    }
}
</style>

@endsection
