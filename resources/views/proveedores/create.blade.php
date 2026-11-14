
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Registrar proveedor</h2>

    <a href="{{ route('proveedores.index') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Volver
    </a>
</div>

<div class="card shadow">
    <div class="card-header text-white"
         style="background-color: #2c3e50;">
        Datos del proveedor
    </div>

    <div class="card-body">

        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf

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
                        value="{{ old('nombre') }}"
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
                        value="{{ old('telefono') }}"
                        maxlength="20"
                        placeholder="Número de contacto"
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
                        value="{{ old('tipo_producto') }}"
                        maxlength="150"
                        placeholder="Ej. medicamentos, material médico"
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
                        value="{{ old('fecha_visita') }}"
                    >

                    @error('fecha_visita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Guardar proveedor
                </button>

                <a href="{{ route('proveedores.index') }}"
                   class="btn btn-outline-secondary">
                    Cancelar
                </a>
            </div>

            <p class="text-muted mt-3 mb-0">
                * Campos obligatorios.
            </p>

        </form>

    </div>
</div>

@endsection
