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
    <title>Política de provicidad — Inventa</title>
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
                                    <a class="nav-link text-info d-flex justify-content-between align-items-center"
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
                                            <a class="nav-link text-info" href="politica_privacidad.php">
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
                                    <a class="nav-link text-secondary" href="acerca_info.php">
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
            <div class="container-fluid p-4">
                <div class="text-center text-black fw-bold">
                    <h5 class="modal-title">Política de Privacidad – Inventa</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="container">
                    <p class="text-muted">
                        Última actualización: <?= date('d/m/Y') ?>
                    </p>

                    <p>
                        En Inventa respetamos y protegemos la privacidad de nuestros usuarios.
                        Esta política explica cómo recopilamos, usamos y protegemos sus datos personales.
                    </p>

                    <h6 class="fw-bold">1. Datos recopilados</h6>
                    <p>
                        Recopilamos datos como nombre de empresa, correo electrónico,
                        teléfono, dirección y credenciales de acceso.
                    </p>

                    <h6 class="fw-bold">2. Finalidad del tratamiento</h6>
                    <p>
                        Los datos se utilizan exclusivamente para:
                    <ul>
                        <li>Gestión de cuentas</li>
                        <li>Funcionamiento del sistema</li>
                        <li>Soporte y comunicación</li>
                    </ul>
                    </p>

                    <h6 class="fw-bold">3. Conservación de datos</h6>
                    <p>
                        Los datos serán conservados mientras exista una relación activa
                        entre el usuario y el sistema.
                    </p>

                    <h6 class="fw-bold">4. Seguridad</h6>
                    <p>
                        Aplicamos medidas técnicas y organizativas para proteger
                        la información contra accesos no autorizados.
                    </p>

                    <h6 class="fw-bold">5. Derechos del usuario</h6>
                    <p>
                        El usuario puede solicitar acceso, rectificación o eliminación
                        de sus datos personales conforme a la legislación aplicable.
                    </p>

                    <h6 class="fw-bold">6. Legislación aplicable</h6>
                    <p>
                        Esta política se adapta a las siguientes normativas:
                    <ul>
                        <li><strong>Perú:</strong> Ley N° 29733 – Ley de Protección de Datos Personales</li>
                        <li><strong>México:</strong> LFPDPPP</li>
                        <li><strong>España / UE:</strong> Reglamento (UE) 2016/679 (RGPD)</li>
                    </ul>
                    </p>

                    <h6 class="fw-bold">7. Cambios en la política</h6>
                    <p>
                        Inventa se reserva el derecho de actualizar esta política.
                        Los cambios serán comunicados dentro del sistema.
                    </p>
                    <h6 class="fw-bold">8. Compartición de datos</h6>
                    <p>
                        Inventa no vende, alquila ni comparte los datos personales
                        con terceros, salvo obligación legal o requerimiento de autoridad competente.
                    </p>
                    <h6 class="fw-bold">9. Responsable del tratamiento</h6>
                    <p>
                        El responsable del tratamiento de los datos es el administrador
                        del sistema Inventa, quien garantiza el uso adecuado de la información.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="js/menu_sidebar.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>