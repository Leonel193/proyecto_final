@extends('layouts.app')

@section('content')

@php
    $listaProductos = $productos->map(fn ($p) => [
        'id' => $p->id,
        'nombre' => $p->nombre . ' - ' . $p->presentacion . ($p->marca ? ' · ' . $p->marca : ''),
    ])->values();
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Registrar nueva compra</h4>
    </div>

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

    <form id="form-compra" action="{{ route('compras.store') }}" method="POST">
        @csrf

        <div class="card mb-4">
            <div class="card-header text-white"
                style="background-color: #2c3e50;">
                <h5 class="mb-0">Datos de la compra</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="proveedor_id" class="form-label mb-0">Proveedor</label>

                            <div class="d-flex gap-2">
                                <a href="{{ route('proveedores.create') }}"
                                   class="btn btn-primary">
                                    Nuevo proveedor
                                </a>
                                <button type="button" id="actualizarProveedores"
                                        class="btn btn-secondary">
                                    Actualizar lista
                                </button>
                            </div>
                        </div>

                        <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                            <option value="">Seleccionar proveedor</option>

                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}"
                                    {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de compra</label>

                        <input
                            type="date"
                            name="fecha_compra"
                            class="form-control"
                            value="{{ old('fecha_compra', now()->toDateString()) }}"
                            max="{{ now()->toDateString() }}"
                            required
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center text-white"
            style="background-color: #2c3e50;">
                <h5 class="mb-0">Productos y lotes</h5>

                <button type="button" id="agregarProducto" class="btn btn-primary btn-sm">
                    Agregar producto
                </button>

                
            </div>
            

            <div class="card-body">

                <div id="avisoSinProductos" class="alert alert-warning d-none">
                    Agrega al menos un producto a la compra.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th style="min-width: 260px;">Producto</th>
                                <th style="width: 110px;">Cantidad</th>
                                <th style="width: 170px;">Precio unitario</th>
                                <th style="width: 170px;">Vencimiento</th>
                                <th style="width: 120px;">Subtotal</th>
                                <th style="width: 100px;">Acción</th>
                            </tr>
                        </thead>

                        <tbody id="listaProductos">
                        </tbody>

                    </table>
                </div>

                <div class="text-end mt-3">
                    <h5>
                        Total de la compra:
                        <span class="text-primary" id="totalCompra">Q 0.00</span>
                    </h5>
                </div>

            </div>
        </div>

        <div class="text-end mb-4">
            <a href="{{route('compras.index')}}" class="btn btn-secondary me-2">
                Cancelar
            </a>

            <button type="submit" id="btnGuardar" class="btn btn-primary">
                Guardar compra
            </button>
        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const productos = @json($listaProductos);
    const filasAnteriores = @json(old('productos', []));
    const minVencimiento = @json(now()->addDay()->toDateString());

    const form = document.getElementById('form-compra');
    const botonAgregar = document.getElementById('agregarProducto');
    const listaProductos = document.getElementById('listaProductos');
    const aviso = document.getElementById('avisoSinProductos');
    const btnGuardar = document.getElementById('btnGuardar');

    let contador = 0;

    function calcularTotal() {
        let total = 0;

        listaProductos.querySelectorAll('tr').forEach(function (fila) {
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(fila.querySelector('.precio').value) || 0;

            const subtotal = Math.round(cantidad * precio * 100) / 100;

            fila.querySelector('.subtotal').textContent = 'Q ' + subtotal.toFixed(2);
            total += subtotal;
        });

        document.getElementById('totalCompra').textContent = 'Q ' + total.toFixed(2);
    }

    function agregarFila(datos, enfocar) {
        datos = datos || {};
        const i = contador++;

        const fila = document.createElement('tr');

        fila.innerHTML = `
            <td>
                <select name="productos[${i}][producto_id]"
                    class="form-select producto" required></select>
            </td>

            <td>
                <input type="number"
                    name="productos[${i}][cantidad]"
                    class="form-control cantidad"
                    min="1" step="1" required>
            </td>

            <td>
                <div class="input-group">
                    <span class="input-group-text">Q</span>
                <input type="number"
                    name="productos[${i}][precio_unitario]"
                    class="form-control precio"
                    min="0.01" step="0.01" required>
                    </div>
            </td>

            <td>
                <input type="date"
                    name="productos[${i}][fecha_vencimiento]"
                    class="form-control vencimiento"
                    min="${minVencimiento}" required>
            </td>

            <td class="subtotal">Q 0.00</td>

            <td>
                <button type="button"
                    class="btn btn-danger btn-sm eliminarProducto">
                    Eliminar
                </button>
            </td>
        `;

        // Opciones del select (se crean por DOM para evitar problemas con caracteres especiales)
        const select = fila.querySelector('.producto');
        select.add(new Option('Seleccionar', ''));
        productos.forEach(function (p) {
            select.add(new Option(p.nombre, p.id));
        });

        // Valores previos (si el servidor rechazó el formulario)
        select.value = datos.producto_id ?? '';
        fila.querySelector('.cantidad').value = datos.cantidad ?? '';
        fila.querySelector('.precio').value = datos.precio_unitario ?? '';
        fila.querySelector('.vencimiento').value = datos.fecha_vencimiento ?? '';

        fila.querySelector('.cantidad').addEventListener('input', calcularTotal);
        fila.querySelector('.precio').addEventListener('input', calcularTotal);

        listaProductos.appendChild(fila);
        const buscador = new TomSelect(select,{
            placeholder: 'Buscar producto...',
            dropdownParent: 'body',
            maxOptions: null,
        });

        fila.querySelector('.eliminarProducto').addEventListener('click', function () {
            buscador.destroy();
            fila.remove();
            calcularTotal();
        });

        aviso.classList.add('d-none');
        calcularTotal();
        if (enfocar) {
            buscador.focus();
        }
    }

    botonAgregar.addEventListener('click', function () {
        agregarFila({}, true);
    });

    const anteriores = Object.values(filasAnteriores);

    if (anteriores.length > 0) {
        anteriores.forEach(function(datos){
            agregarFila(datos, false);
        });
    } else {
        agregarFila({}, false);
    }

    form.addEventListener('submit', function (e) {
        if (listaProductos.children.length === 0) {
            e.preventDefault();
            aviso.classList.remove('d-none');
            return;
        }

        // Evitar doble envío
        btnGuardar.disabled = true;
        btnGuardar.textContent = 'Guardando...';
    });
    const btnActualizar = document.getElementById('actualizarProveedores');

    btnActualizar.addEventListener('click', async function () {
        const select = document.getElementById('proveedor_id');
        const seleccionado = select.value;

        btnActualizar.disabled = true;

        try {
            const respuesta = await fetch(window.location.href);
            const html = await respuesta.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const nuevo = doc.querySelector('#proveedor_id');

            if (nuevo) {
                select.innerHTML = nuevo.innerHTML;
                select.value = seleccionado;
            }
        } catch (error) {
            console.error('No se pudo actualizar la lista de proveedores', error);
        } finally {
            btnActualizar.disabled = false;
        }
    });
});
</script>

@endsection