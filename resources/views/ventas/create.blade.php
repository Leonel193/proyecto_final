@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Nueva venta</h3>

        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card shadow-sm">
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
                    No hay productos con existencias vigentes para vender.
                </div>

            @else

                <form id="form-venta" action="{{ route('ventas.store') }}" method="POST">
                    @csrf

                    <h5 class="mb-1">Productos de la venta</h5>
                    <p class="text-muted">
                        Escribe la cantidad de cada producto. El sistema vende primero el lote que vence antes
                        y, cuando se agota, continúa con el siguiente.
                    </p>

                    <input
                        type="text"
                        id="buscar"
                        class="form-control mb-3"
                        placeholder="Buscar producto o marca...">

                    <div id="avisoSinCantidad" class="alert alert-warning d-none">
                        Escribe la cantidad de al menos un producto para registrar la venta.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Existencias</th>
                                    <th>Sale primero</th>
                                    <th>Precio de venta</th>
                                    <th style="width: 140px;">Cantidad</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($productos as $producto)
                                    @php $primerLote = $producto->lotes->first(); @endphp

                                    <tr class="fila-producto">

                                        <td>
                                            {{ $producto->nombre }}
                                            <small class="text-muted d-block">
                                                {{ $producto->presentacion }}@if ($producto->marca) · {{ $producto->marca }}@endif
                                            </small>
                                        </td>

                                        <td>
                                            {{ $producto->stock_vigente }}
                                            @if ($producto->lotes->count() > 1)
                                                <small class="text-muted d-block">
                                                    en {{ $producto->lotes->count() }} lotes
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            Lote {{ $primerLote->numero_lote ?? 'sin número' }}
                                            <small class="d-block text-muted">
                                                Vence {{ $primerLote->fecha_vencimiento->format('d/m/Y') }}
                                            </small>
                                            @if ($primerLote->fecha_vencimiento->lte(now()->addDays(90)))
                                                <span class="badge bg-warning text-dark">Por vencer</span>
                                            @endif
                                        </td>

                                        <td>
                                            Q {{ number_format($producto->precio_venta, 2) }}
                                        </td>

                                        <td>
                                            <input
                                                type="number"
                                                name="productos[{{ $producto->id }}]"
                                                class="form-control cantidad-input"
                                                min="0"
                                                max="{{ $producto->stock_vigente }}"
                                                data-precio="{{ $producto->precio_venta }}"
                                                data-nombre="{{ $producto->nombre }} - {{ $producto->presentacion }}"
                                                value="{{ old('productos.' . $producto->id, 0) }}">
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <h5>Total: Q <span id="total">0.00</span></h5>

                        <button type="button" id="btn-guardar" class="btn btn-primary">
                            Registrar venta
                        </button>
                    </div>

                </form>

            @endif

        </div>
    </div>

</div>

{{-- Ventana de confirmación --}}
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

    // Si no hay formulario (no hay productos), no hay nada que configurar
    if (!form) {
        return;
    }

    const buscar = document.getElementById('buscar');
    const filas = document.querySelectorAll('.fila-producto');
    const inputs = document.querySelectorAll('.cantidad-input');
    const totalEl = document.getElementById('total');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnConfirmar = document.getElementById('btn-confirmar');
    const aviso = document.getElementById('avisoSinCantidad');
    const resumen = document.getElementById('resumenVenta');
    const resumenTotal = document.getElementById('resumenTotal');
    const modalEl = document.getElementById('modalConfirmar');

    const modal = (modalEl && typeof bootstrap !== 'undefined')
        ? new bootstrap.Modal(modalEl)
        : null;

    // Filtro de búsqueda
    buscar.addEventListener('input', () => {
        const q = buscar.value.toLowerCase();
        filas.forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // Total estimado (el total real lo calcula el servidor)
    function calcularTotal() {
        let total = 0;
        inputs.forEach(input => {
            total += (parseInt(input.value) || 0) * parseFloat(input.dataset.precio);
        });
        totalEl.textContent = total.toFixed(2);
    }

    inputs.forEach(input => input.addEventListener('input', calcularTotal));
    calcularTotal();

    // Que la tecla Enter no envíe el formulario por accidente
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

    // Al presionar "Registrar venta" se muestra la confirmación
    btnGuardar.addEventListener('click', () => {
        // Respeta los min/max de los campos
        if (!form.reportValidity()) {
            return;
        }

        const items = productosSeleccionados();

        if (items.length === 0) {
            aviso.classList.remove('d-none');
            return;
        }

        aviso.classList.add('d-none');

        // Armar el resumen (textContent evita problemas con caracteres especiales)
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
            // Respaldo si Bootstrap JS no está cargado
            enviarVenta();
        }
    });

    btnConfirmar.addEventListener('click', enviarVenta);
});
</script>

@endsection