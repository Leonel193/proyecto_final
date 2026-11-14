@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Ventas</h3>

        <div class="d-flex gap-2">
            <a href="{{ route('reportes.ventas') }}" class="btn btn-secondary">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Reporte de ventas
            </a>

            <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva venta
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Cerrar">
            </button>
        </div>
    @endif

    @error('motivo_anulacion')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Cerrar">
            </button>
        </div>
    @enderror

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ventas as $venta)
                            @php $anulada = $venta->estado === 'anulada'; @endphp

                            <tr class="{{ $anulada ? 'table-secondary' : '' }}">
                                <td>{{ $venta->id }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $venta->usuario ? $venta->usuario->nombres . ' ' . $venta->usuario->apellidos : 'No registrado' }}
                                </td>

                                <td>
                                    <span class="{{ $anulada ? 'text-decoration-line-through text-muted' : '' }}">
                                        Q {{ number_format($venta->total, 2) }}
                                    </span>
                                </td>

                                <td>
                                    @if ($anulada)
                                        <span class="badge bg-danger">Anulada</span>
                                    @else
                                        <span class="badge bg-success">Completada</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('ventas.show', $venta) }}"
                                           class="btn btn-sm btn-info">
                                            Ver
                                        </a>

                                        @if (Auth::user()->puedeAnularVenta($venta))
                                            <button type="button"
                                                class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAnular"
                                                data-url="{{ route('ventas.anular', $venta) }}"
                                                data-venta="{{ $venta->id }}">
                                                Anular
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay ventas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $ventas->links() }}
            </div>

        </div>
    </div>

</div>

<div class="modal fade" id="modalAnular" tabindex="-1" aria-labelledby="tituloAnular" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formAnular" method="POST" action="">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="tituloAnular">Anular venta</h5>
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
                        class="form-control"
                        rows="3"
                        minlength="5"
                        maxlength="255"
                        required></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnAnularConfirmar" class="btn btn-danger">Anular venta</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalAnular');
    const form = document.getElementById('formAnular');
    const boton = document.getElementById('btnAnularConfirmar');

    modal.addEventListener('show.bs.modal', (e) => {
        const origen = e.relatedTarget;

        form.action = origen.dataset.url;
        document.getElementById('tituloAnular').textContent = 'Anular venta #' + origen.dataset.venta;
        document.getElementById('motivo_anulacion').value = '';

        boton.disabled = false;
        boton.textContent = 'Anular venta';
    });

    form.addEventListener('submit', () => {
        boton.disabled = true;
        boton.textContent = 'Anulando...';
    });
});
</script>

@endsection