@extends('layouts.app')
@section('content')
<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card shadow">
    <div class="card-header bg-info">
        <h4>Información del producto</h4>
    </div>

        <div class="card-body">

            <P>
                <strong>Nombre del producto </strong>
                {{ $producto->nombre }}
            </P>

            <p>
                <strong>Presentación </strong>
                {{ $producto->presentacion }}
            </p>

            <p> 
                <strong>Marca </strong>
                {{ $producto->marca ?? 'No especificada' }}
            </p>

            <p>
                <strong>Forma de venta </strong>
                {{ $producto->forma_venta }}        
            </p>

            <p>
                <strong>Principio activo </strong>
                    {{ $producto->principio_activo ?? 'No especificado' }}
            </p>

            <p>
                <strong>Código de barras </strong>
                {{ $producto->codigo_barra ?? 'No registrado' }}
            </p>

            <p>
                <strong>Categoría </strong>
                {{ $producto->categoria }}
            </p>

            <p>
                <strong>Precio de compra </strong>
                Q {{ number_format($producto->precio_compra, 2) }}
            </p>

            <p > 
                <strong>Precio de venta </strong>
                Q {{ number_format($producto->precio_venta, 2) }}
            </p>

            <p>
                <strong>Stock mínimo </strong>
                {{ $producto->stock_minimo }}
            </p>

            <p>
                <strong>Estado </strong>
                @if ($producto->estado)
                    <span class="badge text-bg-success">Activo</span>
                @else
                    <span class="badge text-bg-secondary">Inactivo</span>
                @endif
            </p>

        <a href="{{ route('productos.index') }}"
            class="btn btn-secondary">
            Volver
        </a> 
    </div>
</div>
</div>
</div>
@endsection
