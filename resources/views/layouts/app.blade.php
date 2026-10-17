```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Farmacia Nazareth')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f9;
            font-family: Arial, sans-serif;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 65px;
            background-color: #3E85B9;
            border-bottom: 1px solid #dee2e6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            z-index: 1000;
        }

        .sidebar {
            position: fixed;
            top: 65px;
            left: 0;
            bottom: 0;
            width: 240px;
            background-color: #173b57;
            padding: 20px 12px;
            overflow-y: auto;
        }

        .sidebar-brand {
            color: white;
            font-size: 19px;
            font-weight: bold;
            text-align: center;
            padding: 12px 5px 25px;
        }

        .sidebar .nav-link {
            color: #e8f0f5;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 6px;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #2876a5;
            color: white;
        }

        .main-content {
            margin-left: 240px;
            padding: 90px 25px 25px;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 190px;
            }

            .main-content {
                margin-left: 190px;
                padding: 85px 12px 20px;
            }

            .topbar {
                padding: 0 12px;
            }
        }
    </style>
</head>

<body>

    <!-- Barra superior -->
    <header class="topbar">
        <span class="fw-bold">Sistema de Gestión de Farmacia</span>
        <span class="text-white">
            <i class="bi bi-person-circle me-1"></i>
            Administración
        </span>
    </header>

    <!-- Menú lateral -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-capsule"></i>
            Farmacia Nazareth
        </div>

        <nav class="nav flex-column">
            <a class="nav-link" href="/">
                <i class="bi bi-house-door-fill"></i> Inicio
            </a>
            

            <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}"
               href="{{ route('productos.index') }}">
                <i class="bi bi-box-seam-fill"></i> Inventario
            </a>

            <a class="nav-link" href="#">
                <i class="bi bi-truck"></i> Proveedores
            </a>

            <a class="nav-link" href="#">
                <i class="bi bi-bag-plus-fill"></i> Compras
            </a>

            <a class="nav-link" href="#">
                <i class="bi bi-cart-fill"></i> Ventas
            </a>

            <a class="nav-link" href="#">
                <i class="bi bi-person-badge-fill"></i> Usuarios
            </a>
        </nav>
    </aside>

    <!-- Contenido de cada página -->
    <main class="main-content">
        @yield('content')
    </main>

</body>
</html>
```