<?php
session_start();
include '../controladores/conexion.php';
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
    <title>Acerca de — Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="../img/logo_principal.png" type="image/svg+xml" />
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
                            <a class="nav-link text-info d-flex justify-content-between align-items-center"
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
                                    <a class="nav-link text-info" href="acerca_info.php">
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
                                        href="controladores/desconectar.php">
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
                <div class="text-center text-black fw-bold">
                    <h1 class="fw-bold mb-1"><span class="text-info">IN</span><span class="text-success">VENTA</span></h1>
                    <p class="text-muted mb-0">
                        Sistema de inventario y ventas
                    </p>
                    <small class="badge bg-primary mt-2">
                        Versión 1.0
                    </small>

                </div>
                <div class="container py-4">
                    <!-- Acerca del sistema -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-circle-info text-info me-2"></i>
                            Acerca del sistema
                        </h5>
                        <p class="text-muted text-justify">
                            Inventa es un sistema integral de gestión de ventas e inventario, diseñado para ayudar a pequeñas y medianas empresas a optimizar sus procesos comerciales, mejorar el control de sus productos y tomar decisiones basadas en información clara y en tiempo real.
                            <br>
                            El sistema permite administrar de forma eficiente el inventario, registrar ventas, gestionar clientes y proveedores, y visualizar reportes estadísticos que facilitan el análisis del rendimiento del negocio.
                        </p>
                    </div>
                    <!-- Información de contacto -->
                    <div class="mb-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-address-book text-success me-2"></i>
                            Información de contacto
                        </h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <!--<li class="mb-2">
                                            <i class="fas fa-envelope me-2"></i>
                                            soporte@inventa.com
                                        </li>-->
                            <li class="mb-2">
                                <i class="fas fa-phone me-2"></i>
                                +51 943 239 039
                            </li>
                            <li>
                                <i class="fas fa-location-dot me-2"></i>
                                Iquitos, Perú
                            </li>
                        </ul>
                    </div>
                    <!-- Redes sociales -->
                    <div class="mb-2">
                        <h5 class="fw-bold">
                            <i class="fas fa-bullhorn text-primary me-2"></i>
                            Síguenos
                        </h5>

                        <div class="d-flex gap-3 fs-4">
                            <a href="https://www.tiktok.com/@codevprotechnology?_r=1&_t=ZS-92YRBPjylcK" class="text-decoration-none text-black" target="_blank">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <a href="https://www.linkedin.com/in/christian-eduardo-rojas-lozano-b36166288/" class="text-decoration-none text-primary" target="_blank">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://www.instagram.com/codevpro?igsh=N2djcjh1MjFwczIw" class="text-decoration-none text-danger" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>

                            <a href="https://www.youtube.com/@codevpro7604" class="text-decoration-none text-danger" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="card-footer text-center bg-light border-0 py-0">
                        <small class="text-muted d-block">
                            Desarrollado por <strong>CoDevPro Technology</strong>
                        </small>
                        <small class="text-muted d-block">
                            Iquitos, Perú
                        </small>
                        <small class="text-muted d-block">
                            © <?= date('Y') ?> Todos los derechos reservados
                        </small>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/menu_sidebar.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>