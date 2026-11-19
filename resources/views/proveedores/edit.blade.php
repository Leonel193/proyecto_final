@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">

                <div class="card-header text-white"
                    style="background-color: #2c3e50;">
                    <h4 class="mb-0">
                        Editar proveedor
                    </h4>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('proveedores.update', $proveedor->id) }}"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Nombre --}}
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">
                                    Nombre del proveedor *
                                </label>

                                <input
                                    type="text"
                                    name="nombre"
                                    id="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $proveedor->nombre) }}"
                                    maxlength="150"
                                    required
                                >

                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Teléfono --}}
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    name="telefono"
                                    id="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $proveedor->telefono) }}"
                                    maxlength="20"
                                >

                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tipo de producto --}}
                            <div class="col-md-6">
                                <label for="tipo_producto" class="form-label">
                                    Tipo de producto que distribuye *
                                </label>

                                <input
                                    type="text"
                                    name="tipo_producto"
                                    id="tipo_producto"
                                    class="form-control @error('tipo_producto') is-invalid @enderror"
                                    value="{{ old('tipo_producto', $proveedor->tipo_producto) }}"
                                    maxlength="150"
                                    required
                                >

                                @error('tipo_producto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Fecha de visita --}}
                            <div class="col-md-6">
                                <label for="fecha_visita" class="form-label">
                                    Fecha de próxima visita
                                </label>

                                <input
                                    type="date"
                                    name="fecha_visita"
                                    id="fecha_visita"
                                    class="form-control @error('fecha_visita') is-invalid @enderror"
                                    value="{{ old('fecha_visita', $proveedor->fecha_visita) }}"
                                >

                                @error('fecha_visita')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4 d-flex gap-2">

                            <button class="btn btn-warning">
                                Actualizar
                            </button>

                            <a href="{{ route('proveedores.index') }}"
                            class="btn btn-secondary">
                                Cancelar
                            </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection