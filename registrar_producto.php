<?php
session_start();
if (!isset($_SESSION['usId'])) {
    header("Location: login.php");
    exit();
}
include 'controladores/conexion.php';
include 'controladores/procesar_registro_producto.php';
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
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro producto - Inventa</title>
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
                    <a class="nav-link text-info d-flex justify-content-between align-items-center"
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
                            <a class="nav-link text-info" href="registrar_producto.php">
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
            <div class="container-fluid p-3">
                <!-- Título -->
                <h2 class="mb-3">Registrar producto</h2>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <!-- Imagen -->
                            <div class="mb-2">
                                <div class="card border-0 shadow-sm">
                                    <!-- Input -->
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="bi bi-image"></i>
                                        </span>
                                        <input
                                            type="file"
                                            name="imagen"
                                            id="imagen"
                                            class="form-control"
                                            accept="image/png, image/jpeg">
                                    </div>

                                    <div class="form-text">
                                        Formatos permitidos: JPG, PNG · Tamaño máximo: 1.8 MB
                                    </div>
                                    <div class="card-body p-2">
                                        <!-- Vista previa -->
                                        <div id="previewImagen" class="mt-0 d-none">
                                            <div class="row align-items-center g-3">

                                                <!-- Imagen -->
                                                <div class="col-auto">
                                                    <div class="border rounded p-2 bg-light">
                                                        <img
                                                            id="previewImg"
                                                            class="img-fluid rounded"
                                                            style="width: 70px; height: 60px; object-fit: cover;">
                                                    </div>
                                                </div>

                                                <!-- Detalles -->
                                                <div class="col">
                                                    <ul class="list-group list-group-flush small">
                                                        <!--<li class="list-group-item px-0">
                                                        <i class="bi bi-file-earmark-text text-success me-2"></i>
                                                        <strong>Nombre:</strong>
                                                        <span id="imgNombre"></span>
                                                    </li>-->
                                                        <li class="list-group-item px-0">
                                                            <i class="bi bi-aspect-ratio text-info me-2"></i>
                                                            <strong>Tipo:</strong>
                                                            <span id="imgTipo"></span>
                                                        </li>
                                                        <li class="list-group-item px-0">
                                                            <i class="bi bi-hdd text-warning me-2"></i>
                                                            <strong>Tamaño:</strong>
                                                            <span id="imgSize"></span>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <label for="codigo" class="form-label">SKU/CÓDIGO</label>
                                    <div class="input-group">
                                        <!--codigo del producto-->
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-tag-fill"></i></span>
                                        <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Codigo del producto" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="producto" class="form-label">Producto</label>
                                    <div class="input-group">
                                        <!--nombre del producto-->
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-box-seam"></i></span>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del producto" required>
                                    </div>
                                </div>
                            </div>
                            <!--prcio venta + precio compra -->
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <label for="costo" class="form-label">Costo de compra</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-cash-coin"></i></span>
                                        <input type="number" step="0.01" min="0" class="form-control" id="precio_compra" name="precio_compra" placeholder="Costo de compra" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="precio" class="form-label">Precio a vender</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="bi bi-cash-coin"></i>
                                        </span>
                                        <input type="number" step="0.01" min="0" id="precio_venta"
                                            class="form-control" name="precio_venta"
                                            placeholder="Precio de venta" required>
                                    </div>
                                </div>
                            </div>

                            <!-- opciones de categoria + opciones de departamento -->
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"> <i class="fas fa-th-large"></i></span>
                                        <!--poner un select con opciones de rubro y mostrar los rubros de la base de datos-->
                                        <?php
                                        $sql = "SELECT id_categorias, nombre, id_user FROM categorias where Eliminado = 0 AND id_user=" . intval($_SESSION['usId']) . "";
                                        $resultado = $conexion->query($sql);
                                        ?>
                                        <select class="form-select" id="categoria" name="categoria" required>
                                            <option value="" disabled selected>Selecciona</option>
                                            <?php
                                            if ($resultado->num_rows > 0) {
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo '<option value="' . $fila['id_categorias'] . '">' . $fila['nombre'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="marca" class="form-label">Marca</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">
                                            <!--Poner icono de marca-->
                                            <i class="fas fa-industry me-2"></i>
                                        </span>
                                        <!--poner un select con opciones de rubro y mostrar los rubros de la base de datos-->
                                        <?php
                                        $sql = "SELECT id_marca, nombre, id_user FROM marcas where Eliminado = 0 AND id_user=" . intval($_SESSION['usId']) . "";
                                        $resultado = $conexion->query($sql);
                                        ?>
                                        <select class="form-select" id="marca" name="marca" required>
                                            <option value="" disabled selected>Selecciona</option>
                                            <?php
                                            if ($resultado->num_rows > 0) {
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo '<option value="' . $fila['id_marca'] . '">' . $fila['nombre'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <!--Stock + proveedor-->
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <label for="stock" class="form-label">Stock</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-stack"></i></span>
                                        <input type="number" min="0" step="1" pattern="[0-9]+"
                                            class="form-control" id="stock" name="stock" placeholder="Stock" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="provedor" class="form-label">Proveedor</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"> <i class="fas fa-truck"></i></span>
                                        <!--poner un select con opciones de rubro y mostrar los rubros de la base de datos-->
                                        <?php
                                        $sql = "SELECT id_provedor, nombre, id_user FROM provedores where Eliminado = 0 AND id_user=" . intval($_SESSION['usId']) . "";
                                        $resultado = $conexion->query($sql);
                                        ?>
                                        <select class="form-select" id="proveedor" name="proveedor" required>
                                            <option value="" disabled selected>Selecciona</option>
                                            <option value="">Sin proveedor</option>
                                            <?php
                                            if ($resultado->num_rows > 0) {
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo '<option value="' . $fila['id_provedor'] . '">' . $fila['nombre'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="sucursal" class="form-label">Sucursal / Tienda</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"> <i class="fas fa-store me-2"></i>
                                        </span>
                                        <!--poner un select con opciones de rubro y mostrar los rubros de la base de datos-->
                                        <?php
                                        $sql = "SELECT id_sucursal, nombre, id_user FROM sucursal where Eliminado = 0 AND id_user=" . intval($_SESSION['usId']) . "";
                                        $resultado = $conexion->query($sql);
                                        ?>
                                        <select class="form-select" id="sucursal" name="sucursal" required>
                                            <option value="" disabled selected>Selecciona</option>
                                            <?php
                                            if ($resultado->num_rows > 0) {
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo '<option value="' . $fila['id_sucursal'] . '">' . $fila['nombre'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Breve descripcion del producto (detalle) -->
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción del producto</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Breve descripción del producto"></textarea>
                            </div>
                            <!--Boton registrar-->
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>

                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-<?= $tipoAlerta ?> alert-dismissible fade show" role="alert">
                            <?= $mensaje ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php
                    endif;
                    $conexion->close();
                    ?>
                </div>
            </div>
        </div>
        <script src="js/menu_sidebar.js"></script>
        <script src="js/visualizar_imagen.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>