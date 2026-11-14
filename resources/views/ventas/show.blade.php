@extends('layouts.app')

@section('content')

@php
    $anulada = $venta->estado === 'anulada';
    $puedeAnular = Auth::user()->espropietaria() && !$anulada;
@endphp

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            Venta #{{ $venta->id }}

            @if ($anulada)
                <span class="badge bg-danger">Anulada</span>
            @else
                <span class="badge bg-success">Completada</span>
            @endif
        </h4>

        <div class="d-flex gap-2">
            @if ($puedeAnular)
                <button type="button" class="btn btn-outline-danger"
                        data-bs-toggle="modal" data-bs-target="#modalAnular">
                    Anular venta
                </button>
            @endif

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
        <div class="card-header">
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
        <div class="card-header">
            <h5 class="mb-0">Productos vendidos</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">

                    <thead class="table-light">
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

@if ($puedeAnular)
    {{-- Ventana para anular --}}
    <div class="modal fade" id="modalAnular" tabindex="-1" aria-labelledby="modalAnularTitulo" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('ventas.anular', $venta) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAnularTitulo">Anular venta #{{ $venta->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Las unidades vendidas regresarán a sus lotes y la venta dejará de contarse en los totales.
                            <strong>Esta acción no se puede deshacer.</strong>
                        </p>

                        <label for="motivo_anulacion" class="form-label">Motivo de la anulación</label>

                        <textarea
                            name="motivo_anulacion"
                            id="motivo_anulacion"
                            class="form-control @error('motivo_anulacion') is-invalid @enderror"
                            rows="3"
                            minlength="5"
                            maxlength="255"
                            required>{{ old('motivo_anulacion') }}</textarea>

                        @error('motivo_anulacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Anular venta</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @if ($errors->has('motivo_anulacion'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof bootstrap !== 'undefined') {
                    new bootstrap.Modal(document.getElementById('modalAnular')).show();
                }
            });
        </script>
    @endif
@endif

@endsection