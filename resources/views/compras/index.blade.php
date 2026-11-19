@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Historial de compras</h4>

    <div class="d-flex gap-2">
        <a href="{{ route('reportes.compras') }}" class="btn btn-secondary">
            Reporte de compras
        </a>

        <a href="{{ route('compras.create') }}" class="btn btn-primary">
            Nueva compra
        </a>
    </div>
</div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark"
                        style="--bs-table-bg: #2c3e50; --bs-table-color: #ffffff;">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($compras as $compra)
                            <tr>
                                <td>{{ $compra->numero }}</td>

                                <td>
                                    {{ $compra->fecha_compra->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $compra->proveedor->nombre ?? 'No registrado' }}
                                </td>

                                <td>
                                    {{ $compra->usuario ? $compra->usuario->nombres . ' ' . $compra->usuario->apellidos : 'No registrado' }}
                                </td>

                                <td>
                                    Q {{ number_format($compra->total, 2) }}
                                </td>

                                <td>
                                    <a href="{{ route('compras.show', $compra->id) }}"
                                       class="btn btn-info btn-sm">
                                        Ver
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay compras registradas.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $compras->links() }}
            </div>

        </div>
    </div>

</div>

@endsection