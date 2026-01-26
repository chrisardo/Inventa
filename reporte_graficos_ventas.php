<?php
session_start();
include 'controladores/conexion.php';
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
//llamar al procesador de controladores/procesar_graficos_ventas.php
//include 'controladores/procesar_graficos_ventas.php';

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte ventas — Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />

    <link rel="stylesheet" href="css/graficos.css">
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
                    <a class="nav-link text-info d-flex justify-content-between align-items-center"
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
                            <a class="nav-link text-info" href="reporte_graficos_ventas.php">
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

            <!-- Contenido -->
            <div class="container-fluid p-4">
                <!--Dashboards-->
                <div class="container my-0 py-0">
                    <!--<div class="row mb-5">
                        <div class="col text-center">
                            <h2 class="text-dark">Dashboards</h2>
                        </div>
                    </div>-->
                    <!-- Título -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h2 class="mb-0">Reporte gráficos de las ventas</h2>

                        <div class="btn-group">
                            <button id="btnExportPDF" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </button>

                            <button id="btnExportExcel" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Exportar Excel
                            </button>

                            <button id="btnExportPPT" class="btn btn-warning btn-sm">
                                <i class="fas fa-file-powerpoint"></i> Exportar PPT
                            </button>
                        </div>
                    </div>
                    <form method="GET" id="formFiltros">
                        <div class="row g-3">
                            <div class="col-3 col-md-3">
                                <div class="card border-success mb-3 h-100">
                                    <div class="card-header text-black fw-bold bg-info text-center">Año: </div>
                                    <div class="card-body">
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
                            <div class="col-3 col-md-3">
                                <div class="card border-success mb-3 h-100">
                                    <div class="card-header text-white bg-success text-center">Producto:</div>
                                    <div class="card-body">
                                        <select name="producto" id="producto" class="form-select">
                                            <option value="">-- Todos los productos --</option>
                                            <?php
                                            $sql = "SELECT idProducto, nombre FROM producto where id_user = " . $_SESSION['usId'] . " ORDER BY nombre ASC";
                                            $res = mysqli_query($conexion, $sql);
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                echo "<option value='{$row['idProducto']}'>{$row['nombre']}</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-3 col-md-3">
                                <div class="card border-success mb-3 h-100">
                                    <div class="card-header text-white bg-success text-center">Categoria:</div>
                                    <div class="card-body">
                                        <select name="categoria" id="categoria" class="form-select">
                                            <option value="">-- Todos las categorias --</option>
                                            <?php
                                            $sql = "SELECT id_categorias, nombre FROM categorias where id_user = " . $_SESSION['usId'] . " ORDER BY nombre ASC";
                                            $res = mysqli_query($conexion, $sql);
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                echo "<option value='{$row['id_categorias']}'>{$row['nombre']}</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-3 col-md-3">
                                <div class="card border-success mb-3 h-100">
                                    <div class="card-header text-white bg-success text-center">Cliente:</div>
                                    <div class="card-body">
                                        <select name="cliente" id="cliente" class="form-select">
                                            <option value="">-- Todos los clientes --</option>
                                            <?php
                                            $sql = "SELECT idCliente, nombre FROM clientes where id_user = " . $_SESSION['usId'] . " ORDER BY nombre ASC";
                                            $res = mysqli_query($conexion, $sql);
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                echo "<option value='{$row['idCliente']}'>{$row['nombre']}</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row mb-2 g-3 py-2" id="kpiContainer">
                        <p class="fs-4 lh-base mb-0 fw-bold">KPI (Indicador Clave de Desempeño)</p>
                        <div class="col">
                            <div class="card kpi-card shadow-sm border-success h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">💰 Venta Total</h6>
                                    <h4 id="kpiVenta" class="fw-bold text-success">
                                        S/ 0.00
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card kpi-card shadow-sm border-info h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Ganancia o pérdida</h6>
                                    <h6 id="kpiGanancia_o_perdida" class="fw-bold">—</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card kpi-card shadow-sm border-primary h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">📦 Productos Vendidos</h6>
                                    <h4 id="kpiCantidad" class="fw-bold text-primary">0</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card kpi-card shadow-sm border-warning h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">🏆 TOP Producto</h6>
                                    <h6 id="kpiTopProducto" class="fw-bold">—</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card kpi-card shadow-sm border-info h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">👑 TOP Cliente</h6>
                                    <h6 id="kpiTopCliente" class="fw-bold">—</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <p class="fs-4 lh-base mb-0 fw-bold">Gráficos estadísticos</p>
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white text-center">Monto total vendido por Mes</div>
                                <div class="grafico-container">
                                    <canvas id="compraMes"></canvas>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white text-center">Cantidad de productos vendidos por Mes</div>
                                <div class="grafico-container"><canvas id="cantidadMes"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-2 row-cols-md-0">
                        <div class="col-sm-4">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white text-center">Los 6 productos mas vendidos</div>

                                <div class="grafico-container"><canvas id="topProductos"></canvas></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white text-center">Las 6 categorias mas vendidos</div>

                                <div class="grafico-container"><canvas id="topCategorias"></canvas></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white text-center">Los 6 clientes que mas compran</div>

                                <div class="grafico-container"><canvas id="topClientes"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-2 row-cols-md-0">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div class="card mb-3">
                                <!--Tabla resumen de productos: Producto | cantidad |subTotal | Venta total | Rentabilidad | Utilidad-->
                                <div class="card-header bg-success text-white text-center">Tabla resumen de productos</div>
                                <div class="grafico-container tabla-scroll" id="tablaProductos"></div>
                            </div>
                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div class="card mb-3">
                                <!--Tabla resumen de Categorias: Categorias | cantidad |sub Total| Venta total | Rentabilidad | Utilidad-->
                                <div class="card-header bg-success text-white text-center">Tabla resumen de categorias</div>
                                <div class="grafico-container tabla-scroll" id="tablaCategorias"></div>
                            </div>
                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div class="card mb-3">
                                <!--Tabla resumen de Clientes: Clientes | cantidad |subTotal | Venta total | Rentabilidad | Utilidad-->
                                <div class="card-header bg-success text-white text-center">Tabla resumen de clientes</div>
                                <div class="grafico-container tabla-scroll" id="tablaClientes"></div>
                            </div>
                        </div>

                    </div>
                    <div class="row row-cols-1 row-cols-md-0">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pptxgenjs@3.12.0/dist/pptxgen.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script src="js/dashboards_ventas.js"></script>
    <script src="js/menu_sidebar.js"></script>
    <script>
        const EMPRESA_NOMBRE = "<?= $usuario['nombreEmpresa'] ?>";
        const USUARIO_NOMBRE = "<?= $_SESSION['usuario'] ?? 'Usuario' ?>";
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>