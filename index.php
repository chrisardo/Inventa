<?php
session_start();
//llamar al procesador de index
include 'controladores/procesar_index.php';
// include 'controladores/procesar_dashboards_index.php'
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index — Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
    <!-- Font Awesome para iconos -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/menu_sidebar.css">
    <script src="js/numero.js"></script>
</head>

<body>
    <div class="d-flex vh-100 overflow-hidden">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white p-3 " style="width:250px;">
            <!--poner logo de la empresa-->
            <img src="img/icono_dashboard.png" alt="Logo" class="img-fluid rounded mb-4" height="50">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-info d-flex align-items-center" href="index.php">
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
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
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
                            <a class="nav-link text-secondary" href="ver_ventas.php">
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
                        <li>
                            <a class="nav-link text-secondary" href="departamento.php">
                                <i class="fas fa-building me-2"></i> Departamento
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
                        href="#menuPolitica"
                        role="button"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-user-shield me-2"></i> Política y término
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


            <!-- Contenido -->
            <div class="container-fluid p-3">
                <!-- Título -->
                <p class="fs-3 lh-base mb-3 fw-bold">Bienvenido: <?php echo utf8_decode($usuario['nombreEmpresa']); ?></p>
                <div class="row g-3">
                    <div class="col-3 col-md-3 ">
                        <div class="card kpi-card border-success mb-3 h-100" style="max-width: 18rem;">
                            <div class="card-header text-black fw-bold bg-info text-center">Monto total</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    S/. <span class="kpi-number" data-value="<?php echo number_format($totalVentas, 2); ?>">0</span>
                                </p>

                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-md-3">
                        <div class="card kpi-card border-success h-100 mb-3" style="max-width: 18rem;">
                            <div class="card-header text-white bg-success text-center">Total Clientes</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    <span class="kpi-number" data-value="<?php echo $totalClientes; ?>">0</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-md-3">
                        <div class="card kpi-card border-success h-100 mb-3" style="max-width: 18rem;">
                            <div class="card-header text-white bg-success text-center">Total Productos</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    <span class="kpi-number" data-value="<?php echo $totalProductos; ?>">0</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-md-3">
                        <div class="card kpi-card border-success h-100 mb-3" style="max-width: 18rem;">
                            <div class="card-header text-white bg-success text-center">Total Proveedores</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    <span class="kpi-number" data-value="<?php echo $totalProvedores; ?>">0</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                </div>


                <!--Dashboards-->
                <div class=" ">
                    <!-- Título -->
                    <p class="fs-3 lh-base mb-3 fw-bold">📊Reportes estadisticos</p>
                    <form method="GET" id="formFiltros">

                        <div class="row g-3">
                            <div class="col-md-4 ">
                                <div class="input-group mb-3 border-success">
                                    <span class="input-group-text bg-success text-white">Filtrar por año: </span>
                                    <div class="card-body border-success">
                                        <select name="anio" id="anio" class="form-select">
                                            <option value="">-- Todos los años --</option>
                                            <?php
                                            $sql = "SELECT DISTINCT YEAR(fecha_venta) AS anio FROM ticket_ventas where id_user = " . $_SESSION['usId'] . " ORDER BY anio DESC";
                                            $res = mysqli_query($conexion, $sql);
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                echo "<option value='{$row['anio']}'>{$row['anio']}</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row row-cols-1 row-cols-md-0">
                        <div class="col-sm-6">
                            <div class="card mb-3" style="max-width: 540px">
                                <div class="card-header bg-success text-white text-center">Monto total vendido por Mes</div>
                                <div class="grafico-container"><canvas id="compraMes"></canvas></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card mb-3" style="max-width: 540px">
                                <div class="card-header bg-success text-white text-center">Monto total vendido por día</div>

                                <div class="grafico-container"><canvas id="compraDia"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-0">
                        <div class="col-sm-6">
                            <div class="card mb-3" style="max-width: 540px">
                                <div class="card-header bg-success text-white text-center">Los 6 productos que mas se venden</div>

                                <div class="grafico-container"><canvas id="topProductos"></canvas></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card mb-3" style="max-width: 540px">
                                <div class="card-header bg-success text-white text-center">Los 6 clientes que mas compran</div>

                                <div class="grafico-container"><canvas id="topClientes"></canvas></div>
                            </div>
                        </div>

                    </div>
                    <div class="row row-cols-1 row-cols-md-0">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="card mb-3" style="max-width: 540px">
                                <div class="card-header bg-success text-white text-center">Productos registrados por mes</div>
                                <div class="grafico-container">
                                    <canvas id="productosMes"></canvas>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="card mb-3" style="max-width: 540px">
                                <div class="card-header bg-success text-white text-center">Clientes registrados por mes</div>
                                <div class="grafico-container">
                                    <canvas id="clientesMes"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.8.0/countUp.min.js"></script>

    <script src="js/dashboards_index.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/menu_sidebar.js"></script>
</body>

</html>