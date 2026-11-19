@extends('layouts.app')

@section('content')

<style>
    .sticky-resumen {
        position: sticky;
        bottom: 0;
        z-index: 10;
    }
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Nueva venta</h3>

        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Revisa los siguientes errores:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($productos->isEmpty())

                <div class="alert alert-info mb-0">
                    No hay productos con existencias vigentes y precio de venta para vender.
                </div>

            @else

                <form id="form-venta" action="{{ route('ventas.store') }}" method="POST">
                    @csrf
                    <div class="card-header text-white"
                        style="background-color: #2c3e50;">
                        <h5 class="mb-1">Productos de la venta</h5>
                    </div>

                        <div class="col-md-5 mt-3">
                            <input
                                type="text"
                                    id="buscar"
                                    class="form-control mb-3"
                                    placeholder="Buscar producto">
                        </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-dark"
                                style="--bs-table-bg: #2c3e50; --bs-table-color: #ffffff;">
                                <tr>
                                    <th>Producto</th>
                                    <th>Existencias</th>
                                    <th class="text-end" style="width: 140px;">Precio</th>
                                    <th style="width: 140px;">Cantidad</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($productos as $producto)
                                    @php $primerLote = $producto->lotes->first(); @endphp

                                    <tr class="fila-producto">

                                        <td>
                                            <div class="fw-semibold">{{ $producto->nombre }}</div>
                                            <small class="text-muted">
                                                {{ $producto->presentacion }}@if ($producto->marca) · {{ $producto->marca }}@endif
                                            </small>
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $producto->stock_vigente }}
                                                @if ($producto->lotes->count() > 1)
                                                    <small class="text-muted fw-normal">
                                                        en {{ $producto->lotes->count() }} lotes
                                                    </small>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                · vence {{ $primerLote->fecha_vencimiento->format('d/m/Y') }}
                                            </small>
                                            @if ($primerLote->fecha_vencimiento->lte(now()->addDays(30)))
                                                <span class="badge bg-warning text-dark ms-1">Por vencer</span>
                                            @endif
                                        </td>

                                        <td class="text-end text-nowrap">
                                            Q {{ number_format($producto->precio_venta, 2) }}
                                        </td>

                                        <td>
                                            <input
                                                type="number"
                                                name="productos[{{ $producto->id }}]"
                                                class="form-control cantidad-input"
                                                min="0"
                                                max="{{ $producto->stock_vigente }}"
                                                placeholder="0"
                                                data-precio="{{ $producto->precio_venta }}"
                                                data-nombre="{{ $producto->nombre }} - {{ $producto->presentacion }}"
                                                value="{{ old('productos.' . $producto->id) }}">
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="sticky-resumen bg-white border-top pt-3 pb-2 mt-3">

                        <div id="avisoSinCantidad" class="alert alert-warning py-2 d-none">
                            Escribe la cantidad de al menos un producto para registrar la venta.
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <span class="text-muted">
                                    Productos en la venta: <strong id="cantidadItems">0</strong>
                                </span>
                                <h5 class="mb-0">Total: Q <span id="total">0.00</span></h5>
                            </div>

                            <button type="button" id="btn-guardar" class="btn btn-primary btn-lg">
                                Registrar venta
                            </button>
                        </div>
                    </div>

                </form>

            @endif

        </div>
    </div>

</div>

<div class="modal fade" id="modalConfirmar" tabindex="-1" aria-labelledby="modalConfirmarTitulo" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmarTitulo">Confirmar venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <p>Revisa los productos antes de registrar la venta.</p>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-end" style="width: 100px;">Cantidad</th>
                                <th class="text-end" style="width: 130px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="resumenVenta"></tbody>
                    </table>
                </div>

                <h5 class="text-end mt-3">Total: Q <span id="resumenTotal">0.00</span></h5>

                <p class="text-muted small mb-0">
                    Una vez registrada, para corregir la venta hay que anularla.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                <button type="button" id="btn-confirmar" class="btn btn-success">Confirmar venta</button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-venta');

    if (!form) {
        return;
    }

    const buscar = document.getElementById('buscar');
    const filas = document.querySelectorAll('.fila-producto');
    const inputs = document.querySelectorAll('.cantidad-input');
    const totalEl = document.getElementById('total');
    const itemsEl = document.getElementById('cantidadItems');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnConfirmar = document.getElementById('btn-confirmar');
    const aviso = document.getElementById('avisoSinCantidad');
    const resumen = document.getElementById('resumenVenta');
    const resumenTotal = document.getElementById('resumenTotal');
    const modalEl = document.getElementById('modalConfirmar');

    const modal = (modalEl && typeof bootstrap !== 'undefined')
        ? new bootstrap.Modal(modalEl)
        : null;

    buscar.addEventListener('input', () => {
        const q = buscar.value.toLowerCase();
        filas.forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    function calcularTotal() {
        let total = 0;
        let items = 0;

        inputs.forEach(input => {
            const cantidad = parseInt(input.value) || 0;

            total += cantidad * parseFloat(input.dataset.precio);

            if (cantidad > 0) {
                items++;
            }

            input.closest('tr').classList.toggle('table-success', cantidad > 0);
        });

        totalEl.textContent = total.toFixed(2);
        itemsEl.textContent = items;

        if (items > 0) {
            aviso.classList.add('d-none');
        }
    }

    inputs.forEach(input => {
        input.addEventListener('input', calcularTotal);

        input.addEventListener('focus', () => input.select());
    });

    calcularTotal();

    form.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            e.preventDefault();
        }
    });

    function productosSeleccionados() {
        const items = [];

        inputs.forEach(input => {
            const cantidad = parseInt(input.value) || 0;

            if (cantidad > 0) {
                items.push({
                    nombre: input.dataset.nombre,
                    cantidad: cantidad,
                    subtotal: cantidad * parseFloat(input.dataset.precio),
                });
            }
        });

        return items;
    }

    function enviarVenta() {
        btnGuardar.disabled = true;
        btnConfirmar.disabled = true;
        btnConfirmar.textContent = 'Guardando...';
        form.submit();
    }

    btnGuardar.addEventListener('click', () => {
        if (!form.reportValidity()) {
            return;
        }

        const items = productosSeleccionados();

        if (items.length === 0) {
            aviso.classList.remove('d-none');
            return;
        }

        aviso.classList.add('d-none');

        resumen.innerHTML = '';
        let total = 0;

        items.forEach(item => {
            const tr = document.createElement('tr');

            const tdNombre = document.createElement('td');
            tdNombre.textContent = item.nombre;

            const tdCantidad = document.createElement('td');
            tdCantidad.className = 'text-end';
            tdCantidad.textContent = item.cantidad;

            const tdSubtotal = document.createElement('td');
            tdSubtotal.className = 'text-end';
            tdSubtotal.textContent = 'Q ' + item.subtotal.toFixed(2);

            tr.append(tdNombre, tdCantidad, tdSubtotal);
            resumen.appendChild(tr);

            total += item.subtotal;
        });

        resumenTotal.textContent = total.toFixed(2);

        if (modal) {
            modal.show();
        } else if (confirm('¿Registrar la venta por Q ' + total.toFixed(2) + '?')) {
            enviarVenta();
        }
    });

    btnConfirmar.addEventListener('click', enviarVenta);
});
</script>

@endsection