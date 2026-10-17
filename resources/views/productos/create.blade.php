@extends('layouts.app')
@section('content')

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4>Nuevo Producto</h4>
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productos.store') }}" method="POST">
            @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">
                            Nombre del producto *
                        </label>
                        <input type="text" name="nombre" id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="presentacion" class="form-label">
                            Presentación *
                        </label>
                        <input type="text" name="presentacion"
                            id="presentacion" class="form-control @error('presentacion') is-invalid @enderror"
                            value="{{ old('presentacion') }}" required
                            placeholder="Ej. 500 mg, 10 tabletas">
                        @error('presentacion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" name="marca" id="marca"
                            class="form-control @error('marca') is-invalid @enderror" value="{{ old('marca') }}">
                        @error('marca')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="forma_venta" class="form-label">
                            Forma de venta *
                        </label>
                        <select name="forma_venta" id="forma_venta"
                            class="form-select @error('forma_venta') is-invalid @enderror" required>
                            <option value="">Selecciona una opción</option>
                            @foreach (['Unidad', 'Blíster', 'Caja', 'Frasco', 'Sobre', 'Otro'] as $forma)
                                <option value="{{ $forma }}"
                                    @selected(old('forma_venta') == $forma)>
                                    {{ $forma }}
                                </option>
                            @endforeach
                        </select>
                        @error('forma_venta')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror       
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="principio_activo" class="form-label">
                            Principio activo
                        </label>
                        <input type="text" name="principio_activo"
                            id="principio_activo" class="form-control @error('principio_activo') is-invalid @enderror"
                            value="{{ old('principio_activo') }}">
                        @error('principio_activo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="codigo_barra" class="form-label">
                            Código de barras
                        </label>
                        <input type="text" name="codigo_barra"
                            id="codigo_barra" class="form-control @error('codigo_barra') is-invalid @enderror"
                            value="{{ old('codigo_barra') }}">
                        @error('codigo_barra')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="precio_compra" class="form-label">
                            Precio de compra (Q) *
                        </label>
                        <input type="number" name="precio_compra"
                            id="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror "
                            step="0.01" min="0.01"
                            value="{{ old('precio_compra') }}" required>
                        @error('precio_compra')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="precio_venta" class="form-label">
                            Precio de venta (Q) *
                        </label>
                        <input type="number" name="precio_venta"
                            id="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror"
                            step="0.01" min="0.01"
                            value="{{ old('precio_venta') }}" required>
                        @error('precio_venta')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label for="stock_minimo" class="form-label">
                                Stock mínimo *
                            </label>
                            <input type="number" name="stock_minimo"
                                id="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror   "
                                min="0" value="{{ old('stock_minimo', 0) }}"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="categoria" class="form-label">
                                Categoría *
                            </label>
                            <input type="text" name="categoria" id="categoria"
                                class="form-control @error('categoria') is-invalid @enderror" value="{{ old('categoria') }}"
                                required placeholder="Ej. Analgésicos">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select name="estado" id="estado"
                                class="form-select @error('estado') is-invalid @enderror" required>
                                <option value="1"
                                    @selected(old('estado', '1') == '1')>
                                    Activo
                                </option>
                                <option value="0"
                                    @selected(old('estado') == '0')>
                                    Inactivo
                                </option>
                            </select>
                        </div>
                    </div>

                </div>

                <button class="btn btn-success">
                    Guardar Producto
                </button>

                <a href="{{ route('productos.index') }}"
                    class="btn btn-secondary">
                    Cancelar
                </a>
            </form>
    </div>
</div>

@endsection