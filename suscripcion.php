<?php
session_start();
include 'controladores/conexion.php';
//llamar al procesador de suscripcion
//include 'controladores/procesar_suscripcion.php';
$sqlFoto = "SELECT imagen, nombreEmpresa FROM usuario_acceso WHERE id_user = ?";
$stmt = $conexion->prepare($sqlFoto);
$stmt->bind_param("i", $_SESSION['id_user']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$fotoPerfil = null;
if (!empty($usuario['imagen'])) {
    $fotoPerfil = 'data:image/jpeg;base64,' . base64_encode($usuario['imagen']);
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index — Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
    <link rel="stylesheet" href="css/menu_sidebar.css">
    <!-- Font Awesome para iconos -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white p-3" style="width: 250px;">
            <!--poner logo de la empresa-->
            <img src="img/icono_dashboard.png" alt="Logo" class="img-fluid rounded mb-4" height="50">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center" href="index.php">
                        <i class="fas fa-home me-2"></i>
                        <span class="item-text">Inicio</span>
                    </a>

                </li>
                <li class="nav-item dropdown dropend">
                    <a class="nav-link dropdown-toggle dropdown-toggle-split text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!--Poner icono de productos-->
                        <i class="fas fa-box"></i>Productos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="registrar_producto.php">Registrar producto</a></li>
                        <li><a class="dropdown-item" href="lista_productos.php">Lista de productos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropend">
                    <a class="nav-link dropdown-toggle dropdown-toggle-split text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!--Poner icono de productos-->
                        <i class="fas fa-cart-shopping"></i>Ventas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="ventas.php">Vender</a></li>
                        <li><a class="dropdown-item" href="ver_ventas.php">Ver ventas</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropend">
                    <a class="nav-link text-white dropdown-toggle dropdown-toggle-split" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!--Poner icono de clientes-->
                        <i class="fas fa-users"></i>
                        Clientes
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="registrar_cliente.php">Registrar cliente</a></li>
                        <li><a class="dropdown-item" href="lista_clientes.php">Lista de clientes</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropend">
                    <a class="nav-link text-white dropdown-toggle dropdown-toggle-split" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!--Poner icono de clientes-->
                        <i class="fas fa-truck"></i>
                        Proveedores
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="registrar_proveedor.php">Registrar proveedor</a></li>
                        <li><a class="dropdown-item" href="lista_proveedores.php">Lista de proveedores</a></li>
                    </ul>
                </li>
                <li class="nav-item ">
                    <!--Rubro-->
                    <a class="nav-link text-white " href="categorias.php">
                        <!--Poner icono de categorias-->
                        <i class="fas fa-th-large"></i>
                        Categorias
                    </a>
                </li>
                <li class="nav-item ">
                    <!--Departamento-->
                    <a class="nav-link text-white " href="departamento.php">
                        <!--Poner icono de departamento-->
                        <i class="fas fa-building"></i>
                        Departamento
                    </a>
                </li>
                <!--Rubro-->
                <li class="nav-item">
                    <a class="nav-link text-white " href="rubro.php">
                        <!--Poner icono de rubro-->
                        <i class="fas fa-tags"></i>
                        Rubro
                    </a>
                </li>
                <li class="nav-item dropdown dropend">
                    <a class="nav-link dropdown-toggle dropdown-toggle-split text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!--Poner icono de reporte-->
                        <i class="fas fa-chart-line"></i>
                        Reportes estadísticos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="reporte_graficos_clientes.php">Gráficas estadístcias de clientes</a></li>
                        <li><a class="dropdown-item" href="reporte_graficos_productos.php">Gráficas estadísticas de productos</a></li>
                        <li><a class="dropdown-item" href="#">Gráficas estadísticas de ventas</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="suscripcion.php">
                        <!--Poner icono de suscripcion-->
                        <i class="fas fa-crown me-2"></i>
                        Suscribirse
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="controladores/desconectar.php">
                        <!--Poner icono de cerrar sesion-->
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar sesión
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Contenido principal -->
        <div class="flex-grow-1">
            <!-- Navbar superior FIXED -->
            <nav class="navbar bg-light border-bottom">
                <div class="container-fluid d-flex align-items-center">

                    <!-- Botón sidebar -->
                    <button id="toggleSidebar" class="btn btn-dark me-3">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Título -->
                    <span class="navbar-brand mb-0">
                        Panel
                    </span>

                    <!-- Menú derecho (SIEMPRE visible) -->
                    <ul class="navbar-nav d-flex flex-row align-items-center ms-auto gap-3">

                        <li class="nav-item">
                            <a class="nav-link p-0" href="#">
                                🔔
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 p-0" href="perfil.php">
                                <?php if ($fotoPerfil): ?>
                                    <img src="<?= $fotoPerfil ?>" class="rounded-circle" width="32" height="32">
                                <?php else: ?>
                                    <i class="fas fa-user-circle fa-2x"></i>
                                <?php endif; ?>
                                <span class="d-none d-md-inline">

                                </span>
                            </a>
                        </li>

                    </ul>

                </div>
            </nav>


            <!-- Contenido -->
            <div class="container-fluid p-4">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold">
                            <i class="fas fa-crown text-warning me-2"></i>
                            Planes de Suscripción
                        </h2>
                        <p class="text-muted">
                            Elige el plan que mejor se adapte a tu negocio
                        </p>
                    </div>

                    <div class="row justify-content-center g-4">

                        <!-- PLAN MENSUAL -->
                        <div class="col-md-5 col-lg-4">
                            <div class="card shadow h-100 border-primary">
                                <div class="card-header bg-primary text-white text-center">
                                    <h4 class="mb-0">Plan Mensual</h4>
                                </div>

                                <div class="card-body text-center">
                                    <h1 class="display-6 fw-bold">
                                        S/. 11
                                    </h1>
                                    <p class="text-muted">por mes</p>

                                    <ul class="list-unstyled my-4">
                                        <li><i class="fas fa-check text-success me-2"></i>Acceso completo al sistema</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Ventas ilimitadas</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Soporte técnico</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Actualizaciones</li>
                                    </ul>
                                    <div id="paypal-mensual"></div>
                                </div>
                            </div>
                        </div>

                        <!-- PLAN ANUAL -->
                        <div class="col-md-5 col-lg-4">
                            <div class="card shadow h-100 border-success position-relative">

                                <!-- Badge ahorro -->
                                <!--<span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-success">
                                    Ahorra 20%
                                </span>-->

                                <div class="card-header bg-success text-white text-center">
                                    <h4 class="mb-0">Plan Anual</h4>
                                </div>

                                <div class="card-body text-center">
                                    <h1 class="display-6 fw-bold">
                                        $ 110
                                    </h1>
                                    <p class="text-muted">por año</p>

                                    <ul class="list-unstyled my-4">
                                        <li><i class="fas fa-check text-success me-2"></i>Acceso completo al sistema</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Ventas ilimitadas</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Soporte prioritario</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Actualizaciones</li>
                                    </ul>
                                    <div id="paypal-button-container"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://www.paypal.com/sdk/js?client-id=AQOeswWjLwmuqPv7EsgbJp6-Pq7tSQGjlr5A9PQTV6rKNmBTVZiH2YXKeQa8Ii5BGCkVmYOguvriaCC8&currency=USD"></script>
    <script src="js/procesar_suscripcion.js"></script>
    <script src="js/menu_sidebar.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>