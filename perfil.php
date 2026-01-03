<?php
session_start();
include "controladores/conexion.php"; // Conexión a la BD

if (!isset($_SESSION['usId'])) {
    header("Location: login.php");
    exit();
}
$perfilErrors  = $_SESSION['perfil_errors']  ?? [];
$perfilSuccess = $_SESSION['perfil_success'] ?? null;

unset($_SESSION['perfil_errors'], $_SESSION['perfil_success']);


// Obtener información del usuario
$sqlUsuario = "SELECT id_user, nombreEmpresa, ruc, fecha_registro, imagen , direccion, email, celular, estado FROM usuario_acceso WHERE id_user = ?";
$stmt = $conexion->prepare($sqlUsuario);
$stmt->bind_param("i", $_SESSION['usId']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$fotoPerfil = $usuario['imagen'] ? 'data:image/jpeg;base64,' . base64_encode($usuario['imagen']) : null;

// Obtener tickets del usuario
$sqlTickets = "SELECT COUNT(*) as total_tickets, SUM(total_venta) as total_ventas 
               FROM ticket_ventas WHERE id_user = ?";
$stmtTickets = $conexion->prepare($sqlTickets);
$stmtTickets->bind_param("i", $_SESSION['usId']);
$stmtTickets->execute();
$resTickets = $stmtTickets->get_result();
$tickets = $resTickets->fetch_assoc();

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index — Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
    <link rel="stylesheet" href="css/menu_sidebar.css">
    <link rel="stylesheet" href="css/perfil.css">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/visibilidad_email.js"></script>
</head>

<body>
    <nav class="navbar bg-success navbar-dark sticky-top py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- Regresar -->
            <a href="index.php" class="text-white">
                <i class="fa-solid fa-arrow-left fa-lg"></i>
            </a>

            <!-- Título centrado -->
            <div class="text-center">
                <span class="text-white fw-bold">PERFIL</span>
            </div>

            <!-- Menú derecha -->
            <div class="d-flex align-items-center">
                <a class="dropdown-item text-white" href="#" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">
                    <i class="fa-solid fa-user-pen me-2"></i>

                </a>
                <!-- Menú desplegable -->
                <div class="dropdown">
                    <a class="nav-link text-white p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear fa-lg"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow mt-2">
                        <li>
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editarContrasenaModal" href="#">
                                <i class="fa-solid fa-key me-2"></i>
                                Cambiar contraseña
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editarEmailModal" href="#">
                                <i class="fa-solid fa-envelope me-2"></i>
                                Cambiar email
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- Encabezado del perfil -->
    <div class="profile-header">
        <?php if ($fotoPerfil): ?>
            <img src="<?= $fotoPerfil ?>" class="profile-img-large" alt="Foto Perfil">
        <?php else: ?>
            <i class="fas fa-user-circle profile-icon-large"></i>
        <?php endif; ?>
    </div>



    <!-- Contenido principal -->
    <div class="container mt-0">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h2 class="card-title mb-0"><?= htmlspecialchars($usuario['nombreEmpresa']) ?></h2>
                <div class="user-details text-dark">
                    <p class="mb-1">
                        <i class="fas fa-id-card me-2"></i>RUC: <?= $usuario['ruc'] ?>
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Registrado desde: <?= $usuario['fecha_registro'] ?>
                    </p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-3 col-md-3 ">
                    <div class="card border-success mb-3" style="max-width: 18rem;">
                        <div class="card-header text-white bg-success text-center">
                            <i class="fas fa-map-marker-alt me-2"></i> Dirección
                        </div>
                        <div class="card-body">
                            <p class=" text-center">
                                <?= htmlspecialchars($usuario['direccion']) ?>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-3 col-md-3 ">
                    <div class="card border-success mb-3" style="max-width: 18rem;">
                        <div class="card-header text-white bg-success text-center">
                            <i class="fas fa-envelope me-2"></i>Email:
                        </div>
                        <div class="card-body">
                            <span class="email-text">
                                <?= htmlspecialchars(ocultarEmail($usuario['email'])) ?>
                            </span>
                        </div>


                    </div>
                </div>
                <div class="col-3 col-md-3 ">
                    <div class="card border-success mb-3" style="max-width: 18rem;">
                        <div class="card-header text-white bg-success text-center">
                            <i class="fas fa-phone me-2"></i>Celular:
                        </div>
                        <div class="card-body">
                            <p class="text-center"> <?= htmlspecialchars($usuario['celular']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-3 col-md-3 ">
                    <div class="card border-success mb-3" style="max-width: 18rem;">
                        <div class="card-header text-white bg-success text-center">
                            <i class="fas fa-user-check me-2"></i>Estado:
                        </div>
                        <div class="card-body">
                            <p class=" text-center"> <?= $usuario['estado'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas centradas -->
        <div class="row justify-content-center g-4 mb-3 py-2">
            <div class="col-md-4">
                <div class="card stats-card h-100 text-center">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Tickets de venta</h5>
                        <p class="card-text fs-1"><?= $tickets['total_tickets'] ?? 0 ?></p>
                        <small class="text-muted">Total de transacciones</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card h-100 text-center">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Total ventas</h5>
                        <p class="card-text fs-1">S/. <?= number_format($tickets['total_ventas'] ?? 0, 2) ?></p>
                        <small class="text-muted">Monto total generado</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include 'modal/modal_editar_perfil.php';
    include 'modal/modal_editar_email.php';
    include 'modal/modal_editar_contrasena.php';
    function ocultarEmail($email)
    {
        $partes = explode("@", $email);

        $usuario = $partes[0];
        $dominio = $partes[1];

        // Mostrar solo los primeros 5 caracteres del usuario
        $visible = substr($usuario, 0, 3);
        $oculto  = str_repeat("*", max(strlen($usuario) - 5, 4));

        return $visible . $oculto . "@" . $dominio;
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/actualizar_email.js"></script>
    <script src="js/actualizar_contrasena.js"></script>

</body>

</html>