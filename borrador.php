<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ventas - Inventa</title>

    <link rel="icon" href="img/logo_principal.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/menu_sidebar.css">
</head>

<body>

    <div class="layout">

        <!-- SIDEBAR -->
        <nav id="sidebar" class="bg-dark text-white p-3">
            <img src="img/icono_dashboard.png" class="img-fluid mb-4">

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="ventas.php">
                        <i class="fas fa-cart-shopping"></i> Ventas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="clientes.php">
                        <i class="fas fa-users"></i> Clientes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="controladores/desconectar.php">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </nav>

        <!-- CONTENIDO -->
        <div id="content">

            <!-- NAVBAR SUPERIOR -->
            <nav class="navbar bg-light border-bottom px-3">
                <button id="toggleSidebar" class="btn btn-dark me-3">
                    <i class="fas fa-bars"></i>
                </button>

                <span class="navbar-brand">Panel</span>

                <ul class="navbar-nav ms-auto flex-row gap-3">
                    <li class="nav-item"><a class="nav-link" href="#">🔔</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Perfil</a></li>
                </ul>
            </nav>

            <!-- CONTENIDO PRINCIPAL -->
            <div class="container-fluid p-4">
                <h3>Contenido principal</h3>
                <p>Aquí va todo tu contenido, tablas, gráficos, etc.</p>
            </div>

        </div>

    </div>

    <script src="js/menu_sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>