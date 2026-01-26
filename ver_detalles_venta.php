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
        <nav id="sidebar" class="bg-dark text-white p-3 " style="width:250px;">
            <!--poner logo de la empresa-->
            <img src="img/icono_dashboard.png" alt="Logo" class="img-fluid rounded mb-4" height="50">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center" href="index.php">
                        <i class="fas fa-home me-2"></i>
                        <span class="item-text">Inicio</span>
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#menuInventario"
                        role="button"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-box me-2"></i> Inventario
                        </span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>

                    <ul class="collapse list-unstyled ps-4" id="menuInventario">
                        <li>
                            <a class="nav-link text-secondary" href="lista_productos.php">
                                <i class="fas fa-box"></i> Ver inventario
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="registrar_producto.php">
                                <i class="fas fa-circle-plus me-2"></i> Registrar producto
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="sucursales.php">
                                <!--Poner icono de sucursal-->
                                <i class="fas fa-store me-2"></i>Sucursal/Tienda
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="categorias.php">
                                <!--Poner icono de categorias-->
                                <i class="fas fa-th-large"></i> Categorías
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="marca.php">
                                <!--Poner icono de marca-->
                                <i class="fas fa-industry me-2"></i> Marca
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link text-info d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#menuVentas"
                        role="button"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-cash-register me-2"></i> Ventas
                        </span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>

                    <ul class="collapse list-unstyled ps-4" id="menuVentas">
                        <li>
                            <a class="nav-link text-secondary" href="ventas.php">
                                <i class="fas fa-cart-plus me-2"></i> Vender
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-info" href="ver_ventas.php">
                                <i class="fas fa-receipt me-2"></i> Ver ventas
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#menuClientes"
                        role="button"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-users me-2"></i> Clientes
                        </span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>

                    <ul class="collapse list-unstyled ps-4" id="menuClientes">
                        <li>
                            <a class="nav-link text-secondary" href="registrar_cliente.php">
                                <i class="fas fa-circle-plus me-2"></i> Registrar cliente
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="lista_clientes.php">
                                <i class="fas fa-users me-2"></i> Lista de clientes
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="rubro.php">
                                <i class="fas fa-tags me-2"></i> Rubro
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#menuProveedores"
                        role="button"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-truck me-2"></i> Proveedores
                        </span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>

                    <ul class="collapse list-unstyled ps-4" id="menuProveedores">
                        <li>
                            <a class="nav-link text-secondary" href="registrar_proveedor.php">
                                <i class="fas fa-circle-plus me-2"></i> Registrar proveedor
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="lista_proveedores.php">
                                <i class="fas fa-list me-2"></i> Lista de proveedores
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#menuReportes"
                        role="button"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-chart-line me-2"></i> Reportes
                        </span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>

                    <ul class="collapse list-unstyled ps-4" id="menuReportes">
                        <li>
                            <a class="nav-link text-secondary" href="reporte_graficos_clientes.php">
                                <i class="fas fa-users me-2"></i>
                                Gráficas estadísticas de clientes
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="reporte_graficos_productos.php">
                                <i class="fas fa-box-open me-2"></i>
                                Gráficas estadísticas de productos
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-secondary" href="reporte_graficos_ventas.php">
                                <i class="fas fa-chart-column me-2"></i>
                                Gráficas estadísticas de ventas
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#menuOpciones"
                        role="button"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-gear"></i> Más opciones
                        </span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>

                    <ul class="collapse list-unstyled ps-4" id="menuOpciones">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="metodo_pago.php">
                                <i class="fas fa-credit-card"></i>
                                Método de pago
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="departamento.php">
                                <i class="fas fa-building me-2"></i> Departamento
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#menuPolitica"
                                role="button"
                                aria-expanded="false">
                                <span>
                                    <i class="fas fa-user-shield me-2"></i> Política y términos
                                </span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>

                            <ul class="collapse list-unstyled ps-4" id="menuPolitica">
                                <li>
                                    <a class="nav-link text-secondary" href="politica_privacidad.php">
                                        <i class="fas fa-user-shield me-2"></i> Política de Privacidad
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link text-secondary" href="terminos_condiciones.php">
                                        <i class="fas fa-file-contract me-2"></i> Términos y Condiciones
                                    </a>
                                </li>
                            </ul>

                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="acerca_info.php">
                                <i class="fas fa-info-circle"></i>
                                Acerca de
                            </a>
                        </li>
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
            <!-- Navbar superior FIXED -->
            <nav class="navbar bg-light border-bottom">
                <div class="container-fluid d-flex align-items-center">
                    <!-- Botón sidebar -->
                    <button id="toggleSidebar" class="btn btn-dark me-3  d-lg-none">
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
                                    <img src="<?= $fotoPerfil ?>" class="rounded-circle border-success" width="34" height="34">
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
            <div class="container-fluid p-3">

                <div class="container-fluid position-relative">
                    <div class="container my-0 mb-5 position-relative">
                        <div class="row mb-3">
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
                                    <div class="card-header bg-success text-white">Forma de Pago</div>
                                    <div class="card-body">
                                        <p><?= $venta['metodo_pago'] ?></p>
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