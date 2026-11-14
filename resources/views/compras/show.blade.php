@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Compra #{{ $compra->id }}</h4>

        <a href="{{ route('compras.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Datos de la compra</h5>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-2">
                    <strong>Fecha:</strong><br>
                    {{ $compra->fecha_compra->format('d/m/Y') }}
                </div>

                <div class="col-md-3 mb-2">
                    <strong>Proveedor:</strong><br>
                    {{ $compra->proveedor->nombre ?? 'No registrado' }}
                </div>

                <div class="col-md-3 mb-2">
                    <strong>Registrada por:</strong><br>
                    {{ $compra->usuario ? $compra->usuario->nombres . ' ' . $compra->usuario->apellidos : 'No registrado' }}
                </div>

                <div class="col-md-3 mb-2">
                    <strong>Total:</strong><br>
                    Q {{ number_format($compra->total, 2) }}
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Productos comprados</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                            <th>Lote</th>
                            <th>Vencimiento</th>
                            <th>Existencias actuales del lote</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($compra->detalles as $detalle)
                            @php $lote = $detalle->lotes->first(); @endphp

                            <tr>
                                <td>
                                    {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                                    @if ($detalle->producto)
                                        <small class="text-muted d-block">
                                            {{ $detalle->producto->presentacion }}
                                        </small>
                                    @endif
                                </td>

                                <td>{{ $detalle->cantidad }}</td>

                                <td>Q {{ number_format($detalle->precio_unitario, 2) }}</td>

                                <td>Q {{ number_format($detalle->subtotal, 2) }}</td>

                                <td>{{ $lote?->numero_lote ?? 'Sin número' }}</td>

                                <td>
                                    {{ $lote ? $lote->fecha_vencimiento->format('d/m/Y') : '-' }}
                                </td>

                                <td>{{ $lote?->cantidad ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

@endsection