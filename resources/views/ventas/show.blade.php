@extends('layouts.app')

@section('content')

@php
    $anulada = $venta->estado === 'anulada';
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-11">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>
                    Venta #{{ $venta->numero }}

                    @if ($anulada)
                        <span class="badge bg-danger">Anulada</span>
                    @else
                        <span class="badge bg-success">Completada</span>
                    @endif
                </h4>

                <div class="d-flex gap-2">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                        Volver
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($anulada)
                <div class="alert alert-danger">
                    <strong>Esta venta fue anulada.</strong>
                    Las existencias regresaron a los lotes y no se cuenta en los totales.<br>
                    <strong>Fecha:</strong>
                    {{ $venta->anulada_at ? \Carbon\Carbon::parse($venta->anulada_at)->format('d/m/Y H:i') : '-' }}
                    ·
                    <strong>Anulada por:</strong>
                    {{ $venta->anuladaPor ? $venta->anuladaPor->nombres . ' ' . $venta->anuladaPor->apellidos : 'No registrado' }}
                    <br>
                    <strong>Motivo:</strong> {{ $venta->motivo_anulacion }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header bg-info">
                    <h5 class="mb-0">Datos de la venta</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <strong>Fecha:</strong><br>
                            {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                        </div>

                        <div class="col-md-4 mb-2">
                            <strong>Registrada por:</strong><br>
                            {{ $venta->usuario ? $venta->usuario->nombres . ' ' . $venta->usuario->apellidos : 'No registrado' }}
                        </div>

                        <div class="col-md-4 mb-2">
                            <strong>Total:</strong><br>
                            <span class="{{ $anulada ? 'text-decoration-line-through text-muted' : '' }}">
                                Q {{ number_format($venta->total, 2) }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info">
                    <h5 class="mb-0">Productos vendidos</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">

                            <thead class="table-dark"
                                style="--bs-table-bg: #2c3e50; --bs-table-color: #ffffff;">
                                <tr>
                                    <th>Producto</th>
                                    <th>Lote</th>
                                    <th>Vencimiento</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($venta->detalles as $detalle)
                                    <tr>
                                        <td>
                                            {{ $detalle->lote->producto->nombre ?? 'Producto eliminado' }}
                                            @if ($detalle->lote && $detalle->lote->producto)
                                                <small class="text-muted d-block">
                                                    {{ $detalle->lote->producto->presentacion }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>{{ $detalle->lote->numero_lote ?? 'Sin número' }}</td>

                                        <td>
                                            {{ $detalle->lote ? $detalle->lote->fecha_vencimiento->format('d/m/Y') : '-' }}
                                        </td>

                                        <td>{{ $detalle->cantidad }}</td>

                                        <td>Q {{ number_format($detalle->precio_unitario, 2) }}</td>

                                        <td>Q {{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection