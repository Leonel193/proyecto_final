
@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Registrar usuario</h3>

        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="nombres"
                            class="form-control"
                            value="{{ old('nombres') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos"
                            class="form-control"
                            value="{{ old('apellidos') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre de usuario</label>
                        <input type="text" name="usuario"
                            class="form-control"
                            value="{{ old('usuario') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono"
                            class="form-control"
                            value="{{ old('telefono') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo"
                            class="form-control"
                            value="{{ old('correo') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select" required>
                            <option value="">Seleccione un rol</option>
                            <option value="propietaria">Propietaria</option>
                            <option value="vendedor">Vendedor</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password"
                            class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation"
                            class="form-control" required>
                    </div>

                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        Guardar usuario
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
