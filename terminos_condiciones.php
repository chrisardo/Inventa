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
    <title>Términos y condiciones — Inventa</title>
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
                                            <a class="nav-link text-secondary" href="politica_privacidad.php">
                                                <i class="fas fa-user-shield me-2"></i> Política de Privacidad
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-info" href="terminos_condiciones.php">
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
                    <h5 class="modal-title">
                        Términos y Condiciones de Uso – Inventa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="container">

                    <p class="text-muted">
                        <strong>Última actualización:</strong> <?= date('d/m/Y') ?>
                    </p>

                    <p>
                        Bienvenido a <strong>Inventa</strong>, un sistema de gestión diseñado para
                        facilitar el control y administración de información empresarial.
                        Al registrarse y utilizar este sistema, usted acepta expresamente
                        los siguientes términos y condiciones.
                    </p>

                    <hr>
                    <p>
                    <p>
                        El presente documento constituye un contrato de prestación de servicios
                        bajo modalidad Software as a Service (SaaS) celebrado entre
                        Codevpro Technology y el usuario registrado.
                    </p>

                    <h6 class="fw-bold">1. Aceptación de los términos</h6>
                    <p>
                        El acceso, registro y uso del sistema Inventa implica la aceptación
                        plena y sin reservas de los presentes Términos y Condiciones.
                        Si el usuario no está de acuerdo con alguno de ellos,
                        deberá abstenerse de utilizar el sistema.
                    </p>

                    <h6 class="fw-bold">2. Registro de usuario</h6>
                    <p>
                        El usuario se compromete a proporcionar información veraz,
                        completa y actualizada durante el proceso de registro.
                        Inventa no se responsabiliza por errores derivados de datos incorrectos
                        ingresados por el usuario.
                    </p>

                    <h6 class="fw-bold">3. Uso adecuado del sistema</h6>
                    <p>
                        El sistema deberá ser utilizado únicamente para fines legales
                        y relacionados con la gestión empresarial.
                        Queda estrictamente prohibido el uso del sistema para actividades
                        ilícitas, fraudulentas o que vulneren derechos de terceros.
                    </p>

                    <h6 class="fw-bold">4. Seguridad y confidencialidad</h6>
                    <p>
                        El usuario es responsable de mantener la confidencialidad
                        de sus credenciales de acceso.
                        Cualquier actividad realizada desde su cuenta será considerada
                        como efectuada por el titular de la misma.
                    </p>

                    <h6 class="fw-bold">5. Protección de datos personales</h6>
                    <p>
                        Inventa se compromete a proteger la información personal
                        proporcionada por el usuario, utilizándola únicamente
                        para los fines operativos del sistema.
                        Los datos no serán compartidos con terceros sin autorización,
                        salvo requerimiento legal.
                    </p>

                    <h6 class="fw-bold">6. Disponibilidad del servicio</h6>
                    <p>
                        Inventa no garantiza la disponibilidad ininterrumpida del sistema,
                        ya que pueden producirse interrupciones por mantenimiento,
                        actualizaciones o causas técnicas ajenas a nuestro control.
                    </p>

                    <h6 class="fw-bold">7. Propiedad intelectual</h6>
                    <p>
                        Todo el contenido, diseño, funcionalidades y código del sistema
                        Inventa son propiedad de sus desarrolladores.
                        Queda prohibida la reproducción, modificación o distribución
                        sin autorización expresa.
                    </p>

                    <h6 class="fw-bold">8. Modificaciones de los términos</h6>
                    <p>
                        Inventa se reserva el derecho de modificar estos términos
                        en cualquier momento.
                        Las modificaciones entrarán en vigencia desde su publicación
                        dentro del sistema.
                    </p>

                    <h6 class="fw-bold">9. Responsabilidad</h6>
                    <p>
                        Inventa no se hace responsable por pérdidas de información,
                        daños directos o indirectos derivados del uso del sistema,
                        incluyendo fallos técnicos o uso indebido por parte del usuario.
                    </p>

                    <h6 class="fw-bold">10. Aceptación final</h6>
                    <p>
                        Al marcar la opción <strong>“Aceptar términos y condiciones”</strong>,
                        el usuario declara haber leído, comprendido y aceptado
                        íntegramente el presente documento.
                    </p>
                    <h6 class="fw-bold">11. Suspensión o cancelación de cuentas</h6>
                    <p>
                        Inventa se reserva el derecho de suspender o cancelar cuentas
                        que incumplan estos términos, realicen un uso indebido del sistema
                        o afecten su funcionamiento, sin previo aviso.
                    </p>
                    <h6 class="fw-bold">12. Exactitud de la información</h6>
                    <p>
                        El usuario es el único responsable de la veracidad y exactitud
                        de la información ingresada en el sistema, incluyendo productos,
                        precios, clientes y registros de ventas.
                    </p>
                    <h6 class="fw-bold">13. Emisión de comprobantes de pago electrónicos</h6>
                    <p>
                        El sistema web de inventario y ventas permite la generación de comprobantes
                        de pago electrónicos, tales como boletas de venta y facturas, al momento
                        de registrar una operación comercial.
                        En caso el usuario no seleccione el tipo de comprobante durante el proceso
                        de venta, podrá efectuar dicha selección posteriormente desde la opción
                        “Ver detalles de la venta”, únicamente por una sola vez.
                        Una vez realizada esta elección, el tipo de comprobante no podrá ser
                        modificado bajo ninguna circunstancia.
                    </p>

                    <h6 class="fw-bold">14. No integración directa con SUNAT</h6>
                    <p>
                        El sistema no se encuentra integrado directa ni automáticamente con la
                        SUNAT para la validación, envío, aceptación o declaración de comprobantes
                        de pago electrónicos.
                        En consecuencia, la generación del comprobante dentro del sistema no
                        implica su validación oficial ante la administración tributaria peruana.
                    </p>

                    <h6 class="fw-bold">15. Obligación exclusiva del usuario respecto a la validación tributaria</h6>
                    <p>
                        El usuario reconoce y acepta que es el único y exclusivo responsable
                        del cumplimiento de sus obligaciones tributarias conforme a la normativa
                        vigente en la República del Perú, incluyendo lo dispuesto por la Ley
                        del Impuesto General a las Ventas, el Código Tributario y las normas
                        emitidas por la SUNAT respecto al Sistema de Emisión Electrónica (SEE).
                    </p>
                    <p>
                        Para la validación y envío oficial de comprobantes electrónicos,
                        el usuario deberá contratar de manera independiente los servicios de un
                        Operador de Servicios Electrónicos (OSE), un Proveedor de Servicios
                        Electrónicos (PSE), o delegar dicha responsabilidad a su contador
                        o asesor tributario, según corresponda.
                    </p>

                    <h6 class="fw-bold">16. Exoneración de responsabilidad</h6>
                    <p>
                        Codevpro Technology no realiza validaciones, envíos, declaraciones,
                        almacenamiento oficial ni comunicaciones de comprobantes electrónicos
                        ante la SUNAT.
                        En tal sentido, Codevpro Technology no asume responsabilidad alguna
                        por el incumplimiento total o parcial de obligaciones tributarias por
                        parte del usuario, incluyendo pero no limitándose a:
                    </p>
                    <ul>
                        <li>Falta de validación de comprobantes electrónicos.</li>
                        <li>Envío extemporáneo de información tributaria.</li>
                        <li>Errores en la emisión de comprobantes.</li>
                        <li>Multas, sanciones, cierres temporales o cualquier medida administrativa impuesta por la SUNAT.</li>
                    </ul>

                    <h6 class="fw-bold">17. Multas y sanciones tributarias</h6>
                    <p>
                        El usuario declara conocer que el incumplimiento de las obligaciones
                        relacionadas con la emisión y validación de comprobantes electrónicos
                        puede generar infracciones tipificadas en el Código Tributario peruano,
                        las cuales pueden dar lugar a multas, intereses moratorios,
                        sanciones administrativas o cualquier otra medida establecida por la SUNAT.
                    </p>
                    <p>
                        En ningún caso Codevpro Technology será responsable solidaria,
                        subsidiaria ni indirectamente por dichas infracciones,
                        siendo el usuario quien asume íntegramente cualquier contingencia,
                        responsabilidad económica o legal derivada de su actividad comercial.
                    </p>
                    <h6 class="fw-bold">18. Indemnización y responsabilidad frente a terceros</h6>
                    <p>
                        El usuario acepta indemnizar, defender y mantener indemne a
                        <strong>Codevpro Technology</strong>, así como a sus propietarios,
                        desarrolladores, colaboradores y representantes, frente a cualquier
                        reclamación, demanda, procedimiento administrativo, sanción,
                        pérdida, daño, costo o gasto (incluyendo honorarios legales)
                        que pudiera surgir como consecuencia de:
                    </p>

                    <ul>
                        <li>El uso indebido del sistema por parte del usuario.</li>
                        <li>El incumplimiento de obligaciones tributarias o legales.</li>
                        <li>La emisión incorrecta de comprobantes de pago.</li>
                        <li>Información falsa, incorrecta o incompleta registrada en el sistema.</li>
                        <li>Cualquier reclamación presentada por clientes, proveedores o terceros relacionada con las operaciones realizadas mediante el sistema.</li>
                    </ul>

                    <p>
                        En tales casos, el usuario será el único responsable de asumir
                        cualquier costo, indemnización, multa o gasto legal derivado
                        de dichas reclamaciones, liberando completamente de responsabilidad
                        a Codevpro Technology.
                    </p>

                    <h6 class="fw-bold">19. Jurisdicción y legislación aplicable</h6>
                    <p>
                        Los presentes Términos y Condiciones se rigen e interpretan
                        de conformidad con las leyes vigentes de la República del Perú.
                    </p>

                    <p>
                        Cualquier controversia, conflicto o reclamación que pudiera surgir
                        en relación con el uso del sistema, su interpretación,
                        ejecución o cumplimiento, será sometida a la jurisdicción
                        exclusiva de los tribunales competentes del Perú,
                        renunciando expresamente el usuario a cualquier otro fuero
                        o jurisdicción que pudiera corresponderle.
                    </p>
                    <h6 class="fw-bold">20. Limitación de responsabilidad</h6>
                    <p>
                        En la máxima medida permitida por la legislación vigente,
                        Codevpro Technology no será responsable por daños indirectos,
                        incidentales, especiales, consecuenciales o punitivos,
                        incluyendo, sin limitación, pérdida de ingresos,
                        pérdida de datos, lucro cesante, interrupción del negocio
                        o daños derivados del uso o imposibilidad de uso del sistema.
                    </p>

                    <p>
                        En cualquier caso, la responsabilidad total y acumulada
                        de Codevpro Technology frente al usuario, por cualquier
                        concepto y bajo cualquier modalidad de responsabilidad
                        (contractual, extracontractual o de cualquier otra naturaleza),
                        se limitará exclusivamente al monto efectivamente pagado
                        por el usuario por el uso del sistema durante los
                        últimos doce (12) meses anteriores al hecho que originó
                        la reclamación.
                    </p>

                    <p>
                        Si el usuario utiliza el sistema de manera gratuita,
                        la responsabilidad de Codevpro Technology será nula
                        en la medida permitida por la ley.
                    </p>
                    <h6 class="fw-bold">21. Respaldo de información y limitación por pérdida de datos</h6>

                    <p>
                        El usuario reconoce que es el único responsable de la información
                        que registra, almacena y gestiona dentro del sistema,
                        incluyendo pero no limitándose a datos de clientes,
                        productos, ventas, comprobantes y reportes.
                    </p>

                    <p>
                        Si bien Codevpro Technology podrá realizar respaldos
                        periódicos de la información con fines operativos,
                        no garantiza la conservación permanente, ininterrumpida
                        o libre de errores de los datos almacenados.
                    </p>

                    <p>
                        El usuario acepta que es su obligación mantener copias
                        de seguridad externas y adicionales de toda información
                        relevante para su actividad comercial.
                    </p>

                    <p>
                        Codevpro Technology no será responsable por la pérdida,
                        alteración, eliminación, corrupción o acceso no autorizado
                        de información ocasionados por:
                    </p>

                    <ul>
                        <li>Errores del usuario.</li>
                        <li>Fallas de conexión a internet.</li>
                        <li>Problemas en servidores de terceros.</li>
                        <li>Ataques informáticos, virus o accesos no autorizados.</li>
                        <li>Casos fortuitos o de fuerza mayor.</li>
                    </ul>

                    <p>
                        En ningún caso la pérdida de información dará lugar
                        a indemnizaciones superiores a lo establecido
                        en la cláusula de Limitación de Responsabilidad.
                    </p>
                    <h6 class="fw-bold">22. Fuerza mayor y exportación de información</h6>

                    <p>
                        Codevpro Technology no será responsable por el incumplimiento,
                        suspensión o retraso en la prestación del servicio cuando ello
                        sea consecuencia de eventos de fuerza mayor o caso fortuito,
                        entendiéndose como tales aquellos hechos imprevisibles,
                        inevitables o fuera del control razonable de la empresa.
                    </p>

                    <p>
                        Se consideran eventos de fuerza mayor, incluyendo pero
                        sin limitarse a:
                    </p>

                    <ul>
                        <li>Desastres naturales (terremotos, inundaciones, incendios, huaicos, etc.).</li>
                        <li>Pandemias, emergencias sanitarias o restricciones gubernamentales.</li>
                        <li>Conflictos sociales, bloqueos, huelgas o disturbios civiles.</li>
                        <li>Actos de terrorismo o sabotaje.</li>
                        <li>Fallos masivos en servicios de energía eléctrica o telecomunicaciones.</li>
                        <li>Caídas de servidores, centros de datos o servicios de terceros.</li>
                        <li>Decisiones o disposiciones emitidas por autoridades gubernamentales.</li>
                    </ul>

                    <p>
                        Durante la vigencia de un evento de fuerza mayor,
                        las obligaciones afectadas quedarán suspendidas
                        mientras dure la imposibilidad de cumplimiento,
                        sin que ello genere derecho a indemnización alguna
                        a favor del usuario.
                    </p>

                    <p>
                        Sin perjuicio de lo anterior, el sistema permite al usuario
                        exportar y descargar su información registrada,
                        incluyendo datos de clientes, empleados, proveedores,
                        inventario y ventas, en formatos PDF o Excel,
                        a través de las funcionalidades disponibles dentro del sistema.
                    </p>

                    <p>
                        El usuario reconoce que es su responsabilidad realizar
                        dichas descargas y mantener respaldos externos cuando
                        lo considere necesario para la continuidad de su negocio.
                    </p>
                    <h6 class="fw-bold">23. Cancelación de ventas y responsabilidad del administrador</h6>

                    <p>
                        El sistema permite la funcionalidad de cancelación de ventas,
                        la cual podrá ser utilizada únicamente por el administrador
                        autorizado de la cuenta o por el usuario que cuente con
                        los permisos correspondientes dentro del sistema.
                    </p>

                    <p>
                        La cancelación de una venta deberá realizarse conforme
                        a las políticas internas, normativa comercial y obligaciones
                        tributarias aplicables a la empresa usuaria.
                    </p>

                    <p>
                        Codevpro Technology no interviene, supervisa ni valida
                        las decisiones adoptadas por el administrador respecto
                        a la anulación o cancelación de ventas registradas en el sistema.
                    </p>

                    <p>
                        En consecuencia, cualquier uso indebido de la función
                        de cancelación, incluyendo la eliminación incorrecta
                        de operaciones comerciales, evasión tributaria,
                        alteración de registros contables o cualquier
                        incumplimiento normativo, será de exclusiva responsabilidad
                        del usuario administrador y de la empresa titular de la cuenta.
                    </p>

                    <p>
                        Codevpro Technology no asumirá responsabilidad
                        administrativa, civil, tributaria ni penal derivada
                        del uso incorrecto de dicha funcionalidad.
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