
@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-2">Panel principal</h3>
    <p class="text-muted mb-4">
        Resumen general de Farmacia Nazareth.
    </p>

    <div class="row g-3">

        <!-- Productos registrados -->
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Productos registrados</p>
                            <h3 class="mb-0">{{ $totalProductos }}</h3>
                        </div>
                        <i class="bi bi-capsule fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock bajo -->
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Productos con stock bajo</p>
                            <h3 class="mb-0">{{ $alertasStockBajo }}</h3>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Próximos a vencer</p>
                            <h3 class="mb-0">{{ $alertasPorVencer }}</h3>
                        </div>
                        <i class="bi bi-clock-history fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos vencidos -->
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Productos vencidos</p>
                            <h3 class="mb-0">{{ $alertasVencidos }}</h3>
                        </div>
                        <i class="bi bi-x-circle fs-1 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas del día -->
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Ventas del día</p>
                            <h3 class="mb-0">{{ $ventasHoy }}</h3>
                        </div>
                        <i class="bi bi-cart-check fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5>Bienvenido al sistema de Farmacia Nazareth</h5>
            <p class="text-muted mb-0">
                Desde este panel puedes consultar el estado general
                del inventario y acceder a los módulos del sistema.
            </p>
        </div>
    </div>

</div>

@endsection
