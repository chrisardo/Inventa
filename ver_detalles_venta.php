<?php
session_start();
if (!isset($_SESSION['usId'])) {
    header("Location: login.php");
    exit();
}

require 'controladores/procesar_ver_detalles_venta.php';
$sqlFoto = "SELECT imagen, nombreEmpresa FROM usuario_acceso WHERE id_user = ?";
$stmt = $conexion->prepare($sqlFoto);
$stmt->bind_param("i", $_SESSION['usId']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$fotoPerfil = null;
if (!empty($usuario['imagen'])) {
    $fotoPerfil = 'data:image/jpeg;base64,' . base64_encode($usuario['imagen']);
}
$mensaje = "";
$tipoAlerta = "";
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial de ventas - Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/menu_sidebar.css">
    <!-- Font Awesome para iconos -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex vh-100 overflow-hidden">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white p-3" style="width: 250px;">
            <!--poner logo de la empresa-->
            <img src="img/icono_dashboard.png" alt="Logo" class="img-fluid rounded mb-4" height="50">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php">
                        <!--Poner icono de inicio-->
                        <i class="fas fa-home"></i>
                        Inicio

                    </a>
                </li>
                <li class="nav-item dropdown dropend">
                    <a class="nav-link dropdown-toggle dropdown-toggle-split text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!--Poner icono de productos-->
                        <i class="fas fa-box"></i>Productos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="registrar_producto.php">Registrar producto</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="lista_productos.php">Lista de productos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropend">
                    <a class="nav-link dropdown-toggle dropdown-toggle-split text-info" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!--Poner icono de productos-->
                        <i class="fas fa-cart-shopping"></i>Ventas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="ventas.php">Vender</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-info" href="ver_ventas.php">Ver ventas</a></li>
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
                        <li>
                            <hr class="dropdown-divider">
                        </li>
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
                        <li>
                            <hr class="dropdown-divider">
                        </li>
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
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="reporte_graficos_productos.php">Gráficas estadísticas de productos</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="reporte_graficos_ventas.php">Gráficas estadísticas de ventas</a></li>

                    </ul>
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
        <div id="content">
            <!-- Navbar superior -->
            <nav class="navbar bg-light border-bottom">
                <div class="container-fluid d-flex align-items-center">

                    <!-- Botón sidebar -->
                    <button id="toggleSidebar" class="btn btn-dark me-3 d-lg-none">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Título -->
                    <span class="navbar-brand mb-0">
                        Panel de control
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
            <!-- Encabezado estilo Bootstrap -->
            <header class="bg-success text-white py-3 mt-0">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Título a la izquierda -->
                        <div class="col-12 col-md-6">
                            <h1 class="h4 fw-bold mb-0"></h1>
                        </div>
                        <!-- Breadcrumb a la derecha -->
                        <div class="col-12 col-md-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-md-end mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="ver_ventas.php" class="text-white text-decoration-none">Historial ventas </a>
                                    </li>

                                    <li
                                        class="breadcrumb-item active text-info"
                                        aria-current="page">
                                        Detalle de la venta
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Contenido -->
            <div class="container-fluid p-4">

                <div class="container-fluid position-relative">
                    <div class="container my-0 mb-5 position-relative">
                        <div class="row mb-5">
                            <div class="col text-center">
                                <h2 class="fw-bold text-success">
                                    Detalle de la venta
                                </h2>
                            </div>

                        </div>
                        <div class="row g-3">
                            <div class="col-3 col-md-3">
                                <div class="card border-success h-100">
                                    <div class="card-header bg-success text-white">Serie</div>
                                    <div class="card-body">
                                        <p><?= $venta['serie_venta'] ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="card border-success h-100">
                                    <div class="card-header bg-success text-white">Cliente</div>
                                    <div class="card-body">
                                        <p><?= $venta['cliente'] ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="card border-success h-100">
                                    <div class="card-header bg-success text-white">Pago</div>
                                    <div class="card-body">
                                        <p><?= $venta['forma_pago'] ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="card border-primary h-100">
                                    <div class="card-header bg-primary text-white">Total</div>
                                    <div class="card-body text-primary">
                                        <strong>S/. <?= number_format($venta['total_venta'], 2) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-o">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Mas información:
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-hover table-sm w-100">
                                    <thead class="table-success text-center">
                                        <tr>
                                            <th>Pago del cliente</th>
                                            <th>Vuelto</th>
                                            <th>Estado venta</th>
                                            <th>Vendido el:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>S/. <?= number_format($venta['pago_cliente'], 2) ?></td>
                                            <td>S/. <?= number_format($venta['vuelto_venta'], 2) ?></td>
                                            <td><?= $venta['estado_venta'] ?></td>
                                            <td><?= $venta['fecha_venta'] . " " . $venta['hora_venta'] ?></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabla -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Lista de productos vendidos: <?php //echo $totalVentasRealizadas; 
                                                                                            ?></h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-hover table-sm w-100">
                                    <thead class="table-success text-center">
                                        <tr>
                                            <th>Imagen</th>
                                            <th>sku/codigo</th>
                                            <th>Producto</th>
                                            <th>Cant. pedido</th>
                                            <th>Precio</th>
                                            <th>Sub total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($prod = $resultadoDetalle->fetch_assoc()): ?>
                                            <tr>
                                                <!-- ✅ IMAGEN -->
                                                <td>
                                                    <?php if (!empty($prod['imagen'])): ?>
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($prod['imagen']) ?>"
                                                            width="60" height="60" style="object-fit:cover;border-radius:5px;">
                                                    <?php else: ?>
                                                        <i class="fas fa-box"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $prod['codigo'] ?></td>
                                                <td><?= $prod['nombre'] ?></td>
                                                <td><?= $prod['cantidad_pedido_producto'] ?></td>
                                                <td>S/. <?= number_format($prod['precio'], 2) ?></td>
                                                <td class="text-primary">S/. <?= number_format($prod['sub_total'], 2) ?></td>
                                            </tr>
                                        <?php endwhile; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/menu_sidebar.js"></script>
</body>

</html>