
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Farmacia Nazareth</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">

    <div class="card shadow border-0" style="width: 100%; max-width: 420px;">

        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Farmacia Nazareth</h3>
                <p class="text-muted">Sistema de administración</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.authenticate') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input
                        type="text"
                        name="usuario"
                        class="form-control"
                        value="{{ old('usuario') }}"
                        placeholder="Ingrese su usuario"
                        required
                        autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Ingrese su contraseña"
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Iniciar sesión
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>
