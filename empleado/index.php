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
    <link rel="icon" href="../img/logo_principal.png" type="image/svg+xml" />
    <meta name="description" content="Sistema de inventario y ventas">
    <meta name="keywords" content="inventa, sistema de inventario y ventas, ejemplo">
    <meta name="author" content="codevpro technology">

    <meta name="robots" content="index, follow">
    <!-- Font Awesome para iconos -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/menu_sidebar.css">
    <script src="../js/numero.js"></script>
</head>

<body>
    <div class="d-flex vh-100 overflow-hidden">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white p-3 ">

            <div class="text-center text-info mb-2">
                <!--poner logo de la empresa-->
                <img src="../img/icono_dashboard.png" alt="Logo" class=" rounded mb-2" width="180" height="45">
                <hr class="mx-auto my-1" style="width: 100%; border-top: 4px solid #fdfefe;">
                <p class="fs-6 lh-base mb-2 fw-bold align-items-center"><?php echo utf8_decode($usuario['nombre']); ?></p>
                <hr class="mx-auto my-1 fw-bold " style="width: 100%; border-top: 4px solid #f9fafa;">
            </div>
            <!-- CONTENEDOR SCROLLEABLE -->
            <div class="sidebar-menu">
                <ul class="nav flex-column">
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
                    </ul>
                </ul>
            </div>

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
                        <li class="nav-item dropdown position-static border-success">
                            <a class="nav-link d-flex align-items-center p-0"
                                href="#"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <?php if ($fotoPerfil): ?>
                                    <img src="<?= $fotoPerfil ?>"
                                        class="rounded-circle border"
                                        width="36"
                                        height="36"
                                        style="object-fit:cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                <?php endif; ?>

                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 menu-user"
                                style="margin-top: 20px;">

                                <li class="dropdown-header fw-semibold small text-white py-2 rounded-top">
                                    <?= utf8_decode($usuario['nombre']); ?>
                                </li>

                                <li class="bg-white">
                                    <a class="dropdown-item" href="perfil.php">
                                        <i class="fas fa-user me-2 text-success"></i>
                                        Perfil
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger"
                                        href="../controladores/desconectar.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>


            <!-- Contenido -->
            <div class="container-fluid p-3">
                <form method="POST" id="formFiltros">
                    <div class="row g-3">
                        <div class="col-md-6 ">
                            <div class="input-group mb-3 border-success">
                                <span class="input-group-text bg-success text-white">Filtrar por año: </span>
                                <div class="card-body border-success">
                                    <select name="anio" id="anio" class="form-select">
                                        <option value="">-- Todos los años --</option>
                                        <?php
                                        $sql = "
                                                SELECT anio FROM (
                                                    SELECT YEAR(fecha_registro) AS anio
                                                    FROM producto
                                                    WHERE Eliminado = 0 AND id_user = ?

                                                    UNION

                                                    SELECT YEAR(fecha_registro) AS anio
                                                    FROM clientes
                                                    WHERE Eliminado = 0 AND id_user = ?

                                                    UNION

                                                    SELECT YEAR(fecha_venta) AS anio
                                                    FROM ticket_ventas
                                                    WHERE id_user = ?
                                                ) AS anios
                                                ORDER BY anio DESC
                                            ";

                                        $stmt = $conexion->prepare($sql);
                                        $stmt->bind_param("iii", $_SESSION['usId'], $_SESSION['usId'], $_SESSION['usId']);
                                        $stmt->execute();
                                        $res = $stmt->get_result();

                                        while ($row = $res->fetch_assoc()) {
                                            $selected = ($anio == $row['anio']) ? 'selected' : '';
                                            echo "<option value='{$row['anio']}' $selected>{$row['anio']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row mb-2 g-3 py-2">
                    <p class="fs-4 lh-base mb-1 fw-bold">KPI (Indicador Clave de Desempeño)</p>
                    <div class="col">
                        <div class="card kpi-card border-success mb-3 h-100" style="max-width: 18rem;">
                            <div class="card-header text-black fw-bold bg-info text-center">Monto total</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    S/. <span id="kpi-total-ventas" class="kpi-number kpi-decimal">0.00</span>
                                </p>

                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card kpi-card border-success mb-3 h-100" style="max-width: 18rem;">
                            <div class="card-header text-black fw-bold bg-info text-center">Total ventas del día</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    S/. <span id="kpi-total-ventas-dia" class="kpi-number kpi-decimal">0.00</span>
                                </p>

                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card kpi-card border-success h-100 mb-3">
                            <div id="card-ganancia-header"
                                class="card-header text-white text-center bg-success">
                                Ganancia o pérdida
                            </div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base text-center">
                                    S/.
                                    <span id="kpi-ganancia"
                                        class="kpi-number kpi-decimal">
                                        0.00
                                    </span>
                                    <i id="icono-ganancia" class="ms-2"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card kpi-card border-success h-100 mb-3" style="max-width: 18rem;">
                            <div class="card-header text-white bg-success text-center">Total Clientes</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    <span id="kpi-clientes" class="kpi-number kpi-int">0</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card kpi-card border-success h-100 mb-3" style="max-width: 18rem;">
                            <div class="card-header text-white bg-success text-center">Total Productos</div>
                            <div class="card-body">
                                <p class="card-text fs-4 lh-base  text-center">
                                    <span id="kpi-productos" class="kpi-number kpi-int">0</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <!--Dashboards-->
                <div class=" ">
                    <!-- Título -->
                    <p class="fs-4 lh-base mb-3 fw-bold">📊Gráficos estadisticos</p>
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
    <script src="../js/menu_sidebar.js"></script>
</body>

</html>