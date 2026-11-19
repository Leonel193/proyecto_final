@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">

                <div class="card-header text-white"
                    style="background-color: #2c3e50;">
                    <h4 class="mb-0">
                        Editar Producto
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('productos.update', $producto->id) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="nombre" class="form-label">Nombre del producto <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="nombre"
                                       name="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $producto->nombre) }}"
                                       placeholder="Ej: Paracetamol 500mg"
                                       required>
                                       @error('nombre')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                       @enderror    
                                       
                            </div>

                            <div class="col-md-4">
                                <label for="codigo_barra" class="form-label">Código de barras</label>
                                <input type="text"
                                       id="codigo_barra"
                                       name="codigo_barra"
                                       class="form-control"
                                       value="{{ old('codigo_barra', $producto->codigo_barra) }}"
                                       placeholder="Ej: 7501234567890">
                                @error('codigo_barra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="presentacion" class="form-label">Presentación <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="presentacion"
                                       name="presentacion"
                                       class="form-control @error('presentacion') is-invalid @enderror"
                                       value="{{ old('presentacion', $producto->presentacion) }}"
                                       placeholder="Ej: Caja x 20 tabletas"
                                       required>
                                @error('presentacion')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text"
                                       id="marca"
                                       name="marca"
                                       class="form-control @error('marca') is-invalid @enderror"
                                       value="{{ old('marca', $producto->marca) }}">
                                @error('marca')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="categoria"
                                       name="categoria"
                                       class="form-control @error('categoria') is-invalid @enderror"
                                       value="{{ old('categoria', $producto->categoria) }}"
                                       placeholder="Ej: Analgésicos"
                                       required>
                                @error('categoria')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="principio_activo" class="form-label">Principio activo</label>
                                <input type="text"
                                       id="principio_activo"
                                       name="principio_activo"
                                       class="form-control @error('principio_activo') is-invalid @enderror"
                                       value="{{ old('principio_activo', $producto->principio_activo) }}"
                                       placeholder="Ej: Paracetamol">
                                @error('principio_activo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="forma_venta" class="form-label">Forma de venta <span class="text-danger">*</span></label>
                                <select id="forma_venta"
                                        name="forma_venta"
                                        class="form-select"
                                        required>
                                        <option value="">Seleccionar</option>
                                        @foreach (\App\Models\Producto::FORMAS_VENTA as $forma)
                                            <option value="{{ $forma }}"
                                                @selected(old('forma_venta', $producto->forma_venta) == $forma)>
                                                {{ $forma }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Precio de compra</label>
                                <div class="input-group">
                                    <span class="input-group-text">Q</span>
                                    <input type="text"
                                        class="form-control"
                                        value="{{ number_format($producto->precio_compra, 2)}}"
                                        disabled>
                                </div>
                            </div>  

                            <div class="col-md-4">
                                <label for="precio_venta" class="form-label">Precio de venta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Q</span>
                                    <input type="number"
                                           id="precio_venta"
                                           name="precio_venta"
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('precio_venta') is-invalid @enderror"
                                           value="{{ old('precio_venta', $producto->precio_venta) }}"
                                           required>
                                </div>
                                @error('precio_venta')
                                    <div class="text-danger small">{{$message}}</div>
                                    @enderror
                                </div>

                            <div class="col-md-4">
                                <label for="stock_minimo" class="form-label">Stock mínimo <span class="text-danger">*</span></label>
                                <input type="number"
                                       id="stock_minimo"
                                       name="stock_minimo"
                                       step="1"
                                       min="0"
                                       class="form-control @error('stock_minimo') is-invalid @enderror"
                                       value="{{ old('stock_minimo', $producto->stock_minimo) }}"
                                       required>
                                       @error('stock_minimo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                       @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select id="estado"
                                        name="estado"
                                        class="form-select @error('estado') is-invalid @enderror">
                                    <option value="1" {{ (string) old('estado', (int) $producto->estado) === '1' ? 'selected' : '' }}>
                                        Activo
                                    </option>
                                    <option value="0" {{ (string) old('estado', (int) $producto->estado) === '0' ? 'selected' : '' }}>
                                        Inactivo
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">

                        <button class="btn btn-warning">
                        Actualizar
                        </button>

                        <a href="{{ route('productos.index') }}"
                        class="btn btn-secondary">
                        Volver
                        </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
