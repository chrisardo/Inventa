<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login.php");
    exit();
}
include '../controladores/conexion.php';
include 'controladores/procesar_registro_cliente.php';
$sqlFoto = "SELECT imagen, nombre, apellido FROM empleados WHERE id_empleado = ?";
$stmt = $conexion->prepare($sqlFoto);
$stmt->bind_param("i", $_SESSION['id_empleado']);
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
    <title>Clientes - Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="../img/logo_principal.png" type="image/svg+xml" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/menu_sidebar.css">
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
                            <a class="nav-link text-white d-flex align-items-center" href="index.php">
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
                            <a class="nav-link text-info d-flex justify-content-between align-items-center"
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
                                    <a class="nav-link text-info" href="registrar_cliente.php">
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
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                    <h2 class="mb-0">Registrar cliente</h2>

                    <div class="btn-group flex-wrap gap-2">

                        <!-- Ver lista -->
                        <a href="lista_clientes.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users me-1"></i>
                            Ver lista de clientes
                        </a>
                    </div>

                </div>

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
                            <div class="row g-2 mb-3">
                                <div class="col">
                                    <label for="imagen" class="form-label">Cliente:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Cliente" required>
                                    </div>
                                </div>

                                <div class="col">
                                    <label for="imagen" class="form-label">DNI/RUC</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="number" min="0" class="form-control" id="ruc" name="ruc" placeholder="Documento: Ruc o DNI" required>
                                    </div>
                                </div>
                            </div>
                            <!--Direccion + Telefono -->
                            <div class="row g-2 mb-3">
                                <div class="col">
                                    <label for="imagen" class="form-label">Celular</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="fas fa-mobile-alt"></i>
                                        </span>
                                        <input type="number" min="0" class="form-control" id="celular" name="celular" placeholder="Ejemplo: 987654321">
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="imagen" class="form-label">Rubro</label>
                                    <div class="input-group">
                                        <!--Mostrar los rubros de la base de datos-->
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-building"></i></span>
                                        <!--poner un select con opciones de rubro y mostrar los rubros de la base de datos-->
                                        <?php
                                        // Consulta para obtener los rubros del usuario logueado
                                        $sql = "SELECT id_rubro, nombre, id_user FROM rubros WHERE Eliminado = 0  and id_user = " . intval($_SESSION['usId']);
                                        $resultado = $conexion->query($sql);
                                        ?>
                                        <select class="form-select" id="rubro" name="rubro" required>
                                            <option value="" disabled selected>Seleccione el Rubro</option>
                                            <option value="">Sin rubro</option>
                                            <?php
                                            if ($resultado->num_rows > 0) {
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo '<option value="' . $fila['id_rubro'] . '">' . $fila['nombre'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <!-- opciones de departamento + opciones de provincia -->
                            <div class="row g-2 mb-3">
                                <div class="col">
                                    <label for="imagen" class="form-label">Región o Departamento</label>
                                    <div class="input-group">
                                        <!--poner un select con opciones de departamento y mostrar los departamentos de la base de datos-->
                                        <?php
                                        // Consulta para obtener los rubros del usuario logueado
                                        $sql = "SELECT id_departamento, nombre, id_user FROM departamento WHERE Eliminado = 0 AND id_user = " . intval($_SESSION['usId']);
                                        $resultado = $conexion->query($sql);
                                        ?>
                                        <select class="form-select" id="departamento" name="departamento" required>
                                            <option value="" disabled selected>Seleccione la region</option>
                                            <option value="">Sin region o departamento</option>
                                            <?php
                                            if ($resultado->num_rows > 0) {
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo '<option value="' . $fila['id_departamento'] . '">' . $fila['nombre'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col">
                                    <label for="imagen" class="form-label">Provincia</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="provincia" name="provincia" placeholder="Provincia">
                                    </div>
                                </div>

                                <div class="col">
                                    <label for="imagen" class="form-label">Distrito</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="distrito" name="distrito" placeholder="Distrito">
                                    </div>
                                </div>
                            </div>
                            <!--Provincia + Distrito -->
                            <div class="row g-2 mb-3">
                                <div class="col">
                                    <label for="imagen" class="form-label">Dirección</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección">
                                    </div>
                                </div>
                                <!--Email-->
                                <div class="col">
                                    <label for="imagen" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="E-mail">
                                    </div>
                                </div>
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
        <script src="../js/menu_sidebar.js"></script>
        <script src="../js/visualizar_imagen.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>