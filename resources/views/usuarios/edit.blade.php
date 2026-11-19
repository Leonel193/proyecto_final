@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Datos del usuario --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white"
                style="background-color: #2c3e50;">
                    <h4 class="mb-0">
                        Editar usuario
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" id="nombres" name="nombres" maxlength="100"
                                       class="form-control @error('nombres') is-invalid @enderror"
                                       value="{{ old('nombres', $usuario->nombres) }}" required>
                                @error('nombres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" id="apellidos" name="apellidos" maxlength="100"
                                       class="form-control @error('apellidos') is-invalid @enderror"
                                       value="{{ old('apellidos', $usuario->apellidos) }}" required>
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="usuario" class="form-label">Usuario <span class="text-danger">*</span></label>
                                <input type="text" id="usuario" name="usuario" maxlength="50"
                                       class="form-control @error('usuario') is-invalid @enderror"
                                       value="{{ old('usuario', $usuario->usuario) }}" required>
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" maxlength="20"
                                       class="form-control @error('telefono') is-invalid @enderror"
                                       value="{{ old('telefono', $usuario->telefono) }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
                                <input type="email" id="correo" name="correo" maxlength="150"
                                       class="form-control @error('correo') is-invalid @enderror"
                                       value="{{ old('correo', $usuario->correo) }}" required>
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>

                                @if($usuario->id === auth()->id())
                                    {{-- No puede quitarse el rol a sí misma --}}
                                    <select id="rol" class="form-select" disabled>
                                        <option>Propietaria</option>
                                    </select>
                                    <input type="hidden" name="rol" value="propietaria">
                                @else
                                    <select id="rol" name="rol"
                                            class="form-select @error('rol') is-invalid @enderror" required>
                                        <option value="propietaria" @selected(old('rol', $usuario->rol) === 'propietaria')>Propietaria</option>
                                        <option value="vendedor" @selected(old('rol', $usuario->rol) === 'vendedor')>Vendedor</option>
                                    </select>
                                @endif

                                @error('rol')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-warning">Guardar cambios</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Cambiar contraseña --}}
            <div class="card shadow-sm border-0">
                <div class="card-header text-white"
                style="background-color: #2c3e50;">
                    <h5 class="mb-0">Cambiar contraseña</h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('usuarios.password', $usuario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nueva contraseña <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       autocomplete="new-password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Mínimo 8 caracteres.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" autocomplete="new-password" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary">Cambiar contraseña</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection