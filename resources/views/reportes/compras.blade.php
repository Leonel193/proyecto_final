@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Reporte de compras</h3>
            <p class="text-muted mb-0">
                Historial de compras realizadas a proveedores.
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
                <h5>Reporte de compras</h5>
                <p class="text-muted">
                    Fecha de generación: {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($compras as $compra)
                            <tr>
                                <td>{{ $compra->id }}</td>

                                <td>
                                    {{ $compra->fecha_compra->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $compra->proveedor->nombre ?? 'Sin proveedor' }}
                                </td>

                                <td>
                                    {{ $compra->usuario->nombres ?? 'Sin usuario' }}
                                    {{ $compra->usuario->apellidos ?? '' }}
                                </td>

                                <td>
                                    Q {{ number_format($compra->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay compras registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="text-end mt-3">
                <strong>
                    Total de compras: {{ $compras->count() }}
                </strong>
                <br>
                <strong>
                    Monto acumulado: Q {{ number_format($compras->sum('total'), 2) }}
                </strong>
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