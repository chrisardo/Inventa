<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ./login.php");
    exit();
}

require 'controladores/procesar_lista_clientes.php';
$mensaje = "";
$tipoAlerta = "";
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
                                    <a class="nav-link text-secondary" href="registrar_cliente.php">
                                        <i class="fas fa-circle-plus me-2"></i> Registrar cliente
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link text-info" href="lista_clientes.php">
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
            <div class="container-fluid p-3">
                <!-- Barra superior: Registrar (izquierda) | Buscar + Exportar (derecha) -->
                <div class="card p-3 mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                        <!-- LADO IZQUIERDO -->
                        <div>
                            <a href="registrar_cliente.php" class="btn btn-success">
                                <i class="fas fa-circle-plus me-1"></i>
                                Registrar cliente
                            </a>
                        </div>

                        <!-- LADO DERECHO -->
                        <div class="d-flex flex-column flex-md-row gap-2">

                            <!-- Buscador -->
                            <form id="formBuscar" class="d-flex" method="GET" action="lista_clientes.php">
                                <input
                                    id="inputBuscar"
                                    class="form-control me-2"
                                    type="search"
                                    name="buscar"
                                    placeholder="Buscar cliente por nombre, documento o celular"
                                    value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                                <button class="btn btn-outline-success" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                <!-- Tabla -->
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Clientes registrados: <?php echo $totalClientes; ?></h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-hover table-sm w-100">
                                    <thead>
                                        <tr class="table-success">
                                            <th>Imagen</th>
                                            <th>Nombre</th>
                                            <th>documento</th>
                                            <th>Celular</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($fila['imagen']): ?>
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($fila['imagen']); ?>"
                                                            width="50" height="50" />
                                                    <?php else: ?>
                                                        <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                                    <?php endif; ?>

                                                </td>
                                                <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($fila['dni_o_ruc']); ?></td>
                                                <td><?php echo htmlspecialchars($fila['celular']); ?></td>
                                                <td>
                                                    <!-- Boton para enviar mensaje de whatsapp a el cliente: Hola [nombre del cliente], nos comunicamos de Inventa.-->
                                                    <!--<a href="https://wa.me/<?php //echo htmlspecialchars($fila['celular']); 
                                                                                ?>" class="btn btn-sm btn-success" target="_blank">WhatsApp</a>-->

                                                    <?php if (!empty($fila['celular'])): ?>
                                                        <a href="https://wa.me/<?php echo htmlspecialchars($fila['celular']); ?>?text=Hola%20<?php echo urlencode($fila['nombre']); ?>,%20nos%20comunicamos." class="btn btn-sm btn-success" target="_blank">
                                                            <i class="fab fa-whatsapp icono-input-whatsapp"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($fila['email'])): ?>
                                                        <a
                                                            href="mailto:<?php echo htmlspecialchars($fila['email']); ?>
                                                            ?subject=<?php echo urlencode('Mensaje de Inventa'); ?>
                                                            &body=<?php echo urlencode('Hola ' . $fila['nombre'] . ', nos comunicamos de Inventa.'); ?>"
                                                            class="btn btn-sm btn-primary"
                                                            title="Enviar correo">
                                                            <i class="fas fa-envelope"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <!--Boton para editar-->
                                                    <button
                                                        class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditar"
                                                        data-id="<?php echo $fila['idCliente']; ?>"
                                                        data-nombre="<?php echo htmlspecialchars($fila['nombre']); ?>"
                                                        data-dni="<?php echo htmlspecialchars($fila['dni_o_ruc']); ?>"
                                                        data-direccion="<?php echo htmlspecialchars($fila['direccion']); ?>"
                                                        data-celular="<?php echo htmlspecialchars($fila['celular']); ?>"
                                                        data-rubro="<?php echo htmlspecialchars($fila['id_rubro'] ?? ''); ?>"
                                                        data-departamento="<?php echo htmlspecialchars($fila['id_departamento'] ?? ''); ?>"
                                                        data-provincia="<?php echo htmlspecialchars($fila['provincia']); ?>"
                                                        data-distrito="<?php echo htmlspecialchars($fila['distrito']); ?>"
                                                        data-email="<?php echo htmlspecialchars($fila['email']); ?>">
                                                        <i class="fas fa-pen-to-square"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Mostrar el total de registro del la tabla, anterior, siguiente-->
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <div class="fw-bold text-success">
                                Página <?php echo $pagina; ?> de <?php echo $totalPaginas; ?>
                            </div>

                            <!-- Botones de paginación -->
                            <div>
                                <?php if ($pagina > 1): ?>
                                    <a class="btn btn-outline-success btn-sm me-2"
                                        href="?pagina=<?php echo $pagina - 1; ?>&buscar=<?php echo urlencode($busqueda); ?>">
                                        ⬅ Anterior
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-outline-success btn-sm me-2" disabled>⬅ Anterior</button>
                                <?php endif; ?>

                                <?php if ($pagina < $totalPaginas): ?>
                                    <a class="btn btn-outline-success btn-sm"
                                        href="?pagina=<?php echo $pagina + 1; ?>&buscar=<?php echo urlencode($busqueda); ?>">
                                        Siguiente ➡
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-outline-success btn-sm" disabled>Siguiente ➡</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/menu_sidebar.js"></script>
    <!-- llamar modal_clientes.php -->
    <?php include '../modal/modal_clientes.php'; ?>
    <script src="../js/lista_clientes.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>