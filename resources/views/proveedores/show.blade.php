@extends('layouts.app')

@section('content')

<div class=container-fluid>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info">
                    <h4>Información del proveedor</h4>
                </div>

                <div class="card-body">
                <P>
                    <strong>Nombre del proveedor </strong>
                    {{ $proveedor->nombre }}
                </P>

                <p>
                    <strong> Teléfono </strong>
                    {{ $proveedor->telefono ?: 'No registrado' }}
                </p>

                <p>
                    <strong>Tipo de producto </strong>
                    {{ $proveedor->tipo_producto }}
                </p>

                <P>
                    <strong>Fecha de visita </strong>
                    {{ $proveedor->fecha_visita
                        ? \Carbon\Carbon::parse($proveedor->fecha_visita)->format('d/m/Y')
                        : 'No registrada' }}
                </P>

                    <a href="{{ route('proveedores.index') }}"
                    class="btn btn-secondary">
                        Volver
                    </a>
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection
