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
    <title>Política de provicidad — Inventa</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
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
        <nav id="sidebar" class="bg-dark text-white p-3 ">

            <div class="text-center text-info mb-2">
                <!--poner logo de la empresa-->
                <img src="img/icono_dashboard.png" alt="Logo" class=" rounded mb-2" width="180" height="45">
                <hr class="mx-auto my-1" style="width: 100%; border-top: 4px solid #fdfefe;">
                <p class="fs-6 lh-base mb-2 fw-bold align-items-center"><?php echo utf8_decode($usuario['nombreEmpresa']); ?></p>
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
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#menuEmpleados"
                                role="button"
                                aria-expanded="false">
                                <span>
                                    <i class="fas fa-user-tie me-2"></i> Empleados
                                </span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>

                            <ul class="collapse list-unstyled ps-4" id="menuEmpleados">
                                <li>
                                    <a class="nav-link text-secondary" href="registrar_empleado.php">
                                        <i class="fas fa-user-plus me-2"></i> Registrar empleado
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link text-secondary" href="lista_empleados.php">
                                        <i class="fas fa-address-card me-2"></i> Lista de empleados
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
                                        Estadísticas de clientes
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link text-secondary" href="reporte_graficos_productos.php">
                                        <i class="fas fa-box-open me-2"></i>
                                        Estadísticas de productos
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link text-secondary" href="reporte_graficos_ventas.php">
                                        <i class="fas fa-chart-column me-2"></i>
                                        Estadísticas de ventas
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
                                    <a class="nav-link text-secondary" href="metodo_pago.php">
                                        <i class="fas fa-credit-card"></i>
                                        Método de pago
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="departamento.php">
                                        <i class="fas fa-building me-2"></i> Departamento
                                    </a>
                                </li>
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
                                    <?= utf8_decode($usuario['nombreEmpresa']); ?>
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

                    <h6 class="fw-bold">1. Identidad del responsable del tratamiento</h6>
                    <p>
                        El responsable del tratamiento de los datos personales es
                        <strong>Codevpro Technology</strong>, titular del sistema Inventa,
                        quien actúa conforme a la legislación vigente en materia de
                        protección de datos personales en la República del Perú.
                    </p>

                    <h6 class="fw-bold">2. Marco normativo aplicable</h6>
                    <p>
                        La presente Política de Privacidad se rige por la Ley N° 29733 –
                        Ley de Protección de Datos Personales y su Reglamento,
                        así como por las disposiciones emitidas por la Autoridad
                        Nacional de Protección de Datos Personales del Perú.
                    </p>

                    <h6 class="fw-bold">3. Datos personales recopilados</h6>
                    <p>
                        Inventa podrá recopilar y tratar los siguientes datos personales:
                    </p>
                    <ul>
                        <li>Nombre o razón social.</li>
                        <li>Número de documento de identidad o RUC.</li>
                        <li>Correo electrónico.</li>
                        <li>Número telefónico.</li>
                        <li>Dirección comercial.</li>
                        <li>Credenciales de acceso al sistema.</li>
                        <li>Información registrada por el usuario dentro del sistema (clientes, empleados, proveedores, ventas, inventario).</li>
                    </ul>

                    <h6 class="fw-bold">4. Finalidad del tratamiento</h6>
                    <p>
                        Los datos personales son tratados exclusivamente para:
                    </p>
                    <ul>
                        <li>Crear y administrar cuentas de usuario.</li>
                        <li>Permitir el funcionamiento del sistema web.</li>
                        <li>Brindar soporte técnico y atención al cliente.</li>
                        <li>Garantizar la seguridad y prevención de fraudes.</li>
                        <li>Cumplir obligaciones legales cuando corresponda.</li>
                    </ul>

                    <h6 class="fw-bold">5. Base legal del tratamiento</h6>
                    <p>
                        El tratamiento de datos personales se realiza con el consentimiento
                        del titular de los datos y/o en virtud de la ejecución de una relación
                        contractual derivada del uso del sistema.
                    </p>

                    <h6 class="fw-bold">6. Conservación de la información</h6>
                    <p>
                        Los datos personales serán conservados mientras exista
                        una relación contractual activa con el usuario y durante
                        el tiempo necesario para cumplir obligaciones legales.
                    </p>

                    <h6 class="fw-bold">7. Confidencialidad y seguridad</h6>
                    <p>
                        Codevpro Technology adopta medidas técnicas, organizativas
                        y legales razonables para proteger los datos personales
                        contra pérdida, acceso no autorizado, alteración o divulgación.
                        No obstante, el usuario reconoce que ningún sistema en Internet
                        es absolutamente seguro.
                    </p>

                    <h6 class="fw-bold">8. Transferencia y acceso por terceros</h6>
                    <p>
                        Los datos personales no serán vendidos ni comercializados.
                        Podrán ser compartidos únicamente en los siguientes casos:
                    </p>
                    <ul>
                        <li>Por requerimiento de autoridad competente.</li>
                        <li>Con proveedores tecnológicos necesarios para la operación del sistema (hosting, servidores, soporte técnico).</li>
                        <li>Cuando exista obligación legal expresa.</li>
                    </ul>

                    <h6 class="fw-bold">9. Derechos ARCO</h6>
                    <p>
                        El titular de los datos puede ejercer sus derechos de
                        Acceso, Rectificación, Cancelación y Oposición (ARCO),
                        conforme a la Ley N° 29733.
                    </p>
                    <p>
                        Para ejercer estos derechos, el usuario deberá enviar
                        una solicitud formal al correo electrónico de contacto
                        indicado dentro del sistema, acreditando su identidad.
                    </p>

                    <h6 class="fw-bold">10. Responsabilidad sobre datos de terceros</h6>
                    <p>
                        El usuario que registre datos personales de clientes,
                        empleados o proveedores dentro del sistema declara
                        contar con la autorización correspondiente para su tratamiento,
                        siendo el único responsable frente a dichos terceros.
                    </p>

                    <h6 class="fw-bold">11. Eliminación y portabilidad de datos</h6>
                    <p>
                        El usuario puede solicitar la eliminación de su cuenta
                        y descargar su información en formatos disponibles
                        (PDF o Excel) antes de la cancelación definitiva.
                    </p>

                    <h6 class="fw-bold">12. Cambios en la Política de Privacidad</h6>
                    <p>
                        Codevpro Technology podrá actualizar esta Política
                        de Privacidad en cualquier momento. Las modificaciones
                        serán publicadas dentro del sistema y entrarán en vigencia
                        desde su publicación.
                    </p>

                    <h6 class="fw-bold">13. Jurisdicción</h6>
                    <p>
                        Cualquier controversia relacionada con la protección
                        de datos personales será resuelta conforme a las leyes
                        de la República del Perú.
                    </p>
                    <h6 class="fw-bold">14. Condición de Encargado del Tratamiento</h6>

                    <p>
                        En relación con los datos personales que el usuario registre
                        dentro del sistema (incluyendo datos de clientes, empleados,
                        proveedores u otros terceros), Codevpro Technology actúa
                        exclusivamente en calidad de <strong>Encargado del Tratamiento</strong>,
                        mientras que el usuario tiene la condición de
                        <strong>Responsable del Banco de Datos Personales</strong>,
                        conforme a lo establecido en la Ley N° 29733 y su Reglamento.
                    </p>

                    <p>
                        En tal sentido, el usuario declara bajo su exclusiva responsabilidad:
                    </p>

                    <ul>
                        <li>Contar con el consentimiento válido, previo, informado y expreso de los titulares de los datos personales que registre en el sistema.</li>
                        <li>Haber cumplido con las obligaciones de inscripción del banco de datos ante la autoridad competente, cuando corresponda.</li>
                        <li>Utilizar el sistema únicamente para fines lícitos y conforme a la normativa vigente.</li>
                    </ul>

                    <p>
                        Codevpro Technology no determina la finalidad ni el contenido
                        de los datos personales registrados por el usuario,
                        limitándose únicamente a proporcionar la infraestructura
                        tecnológica para su almacenamiento y gestión.
                    </p>

                    <p>
                        En consecuencia, cualquier denuncia, reclamación,
                        procedimiento administrativo o sanción iniciada ante
                        la Autoridad Nacional de Protección de Datos Personales
                        que derive del tratamiento indebido realizado por el usuario,
                        será de exclusiva responsabilidad de este último,
                        quien se obliga a mantener indemne a Codevpro Technology
                        frente a cualquier contingencia legal o económica.
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