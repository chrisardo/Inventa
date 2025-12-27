<?php
//Toda parte es de controladores/procesar_lista_proveedores.php
// Conexión
include 'conexion.php';


$mensaje = "";
$tipoAlerta = "";
$tipoMensaje = ""; // success, danger, warning, info

// ==== PROCESAR EDICIÓN y GUARDAR CAMBIOS EN LA BASE DE DATOS, PERO PRIMERO VALIDAR QUE EL NOMBRE, CELULAR, DOCUMENTO NO ESTEN VACIOS ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar') {

    $id   = intval($_POST['id_proveedor']);
    $usId = intval($_SESSION['usId']);

    /* ===== VALIDACIONES ===== */
    if (
        empty($_POST['nombre']) ||
        empty($_POST['dni_o_ruc']) ||
        empty($_POST['celular'])
    ) {
        $mensaje = "Nombre, documento y celular son obligatorios.";
        $tipoAlerta = "danger";
        exit();
    }

    if (!isset($_POST['departamento']) || intval($_POST['departamento']) === 0) {
        $mensaje = "Debe seleccionar un departamento.";
        $tipoAlerta = "danger";
        exit();
    }

    /* ===== DATOS ===== */
    $nombre       = trim($_POST['nombre']);
    $ruc          = trim($_POST['dni_o_ruc']);
    $celular      = trim($_POST['celular']);
    $email        = trim($_POST['email'] ?? '');
    $direccion    = trim($_POST['direccion'] ?? '');
    $provincia    = trim($_POST['provincia'] ?? '');
    $distrito     = trim($_POST['distrito'] ?? '');
    $departamento = intval($_POST['departamento']);

    /* ===== VALIDAR IMAGEN (SI EXISTE) ===== */
    $hayImagen = (
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
        !empty($_FILES['imagen']['tmp_name'])
    );

    if ($hayImagen) {

        $permitidos = ['image/jpeg', 'image/png'];
        $tipoMime   = mime_content_type($_FILES['imagen']['tmp_name']);
        $tamanoMax  = 1.5 * 1024 * 1024;

        if (!in_array($tipoMime, $permitidos)) {
            $mensaje = "Solo se permiten imágenes JPG o PNG.";
            $tipoAlerta = "danger";
            exit();
        }

        if ($_FILES['imagen']['size'] > $tamanoMax) {
            $mensaje = "La imagen no debe superar 1.5 MB.";
            $tipoAlerta = "danger";
            exit();
        }
    }

    /* =====================================================
       UPDATE SIN IMAGEN
       ===================================================== */
    if (!$hayImagen) {

        $sql = "UPDATE provedores SET
                    nombre = ?,
                    ruc = ?,
                    celular = ?,
                    email = ?,
                    direccion = ?,
                    provincia = ?,
                    distrito = ?,
                    id_departamento = ?
                WHERE id_provedor = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die("Error prepare(): " . $conexion->error);
        }

        $stmt->bind_param(
            "sssssssiii",
            $nombre,
            $ruc,
            $celular,
            $email,
            $direccion,
            $provincia,
            $distrito,
            $departamento,
            $id,
            $usId
        );
    }
    /* =====================================================
       UPDATE CON IMAGEN
       ===================================================== */ else {

        $sql = "UPDATE provedores SET
                    nombre = ?,
                    ruc = ?,
                    imagen = ?,
                    celular = ?,
                    email = ?,
                    direccion = ?,
                    provincia = ?,
                    distrito = ?,
                    id_departamento = ?
                WHERE id_provedor = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die("Error prepare(): " . $conexion->error);
        }

        $imagen = null;

        $stmt->bind_param(
            "ssbssssiiii",
            $nombre,
            $ruc,
            $imagen,
            $celular,
            $email,
            $direccion,
            $provincia,
            $distrito,
            $departamento,
            $id,
            $usId
        );

        $imagenBinaria = file_get_contents($_FILES['imagen']['tmp_name']);
        $stmt->send_long_data(2, $imagenBinaria);
    }

    /* ===== EJECUTAR ===== */
    if ($stmt->execute()) {
        $mensaje = "Proveedor actualizado correctamente.";
        $tipoAlerta = "success";
    } else {
        $mensaje = "Error al actualizar proveedor: " . $stmt->error;
        $tipoAlerta = "danger";
    }

    $stmt->close();
    header("Location: ./lista_proveedores.php");
    exit();
}






// ==== ELIMINAR ====
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("UPDATE provedores SET Eliminado = 1 WHERE id_provedor = $id");
    //$conexion->query("DELETE FROM proveedores WHERE id_proveedor = $id AND id_user = $id_user AND Eliminado = 0");
    //ir a la pagina de lista_proveedores.php
    @header("Location: ./lista_proveedores.php");
    exit();
}


// ==== PAGINACIÓN CONFIGURACIÓN ====
$porPagina = 5;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $porPagina;

// ==== BUSQUEDA ====
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$whereBusqueda = "";
if ($busqueda !== '') {
    $busquedaEsc = $conexion->real_escape_string($busqueda);
    $whereBusqueda = " AND (
        nombre LIKE '%$busquedaEsc%' 
        OR ruc LIKE '%$busquedaEsc%' 
        OR celular LIKE '%$busquedaEsc%'
    )";
}

// ==== CONSULTA PAGINADA ====
$sql = "
    SELECT * FROM provedores 
    WHERE id_user = $usId and Eliminado = 0
    $whereBusqueda
    ORDER BY id_provedor DESC
    LIMIT $porPagina OFFSET $offset
";
$resultado = $conexion->query($sql);

// ==== TOTAL DE REGISTROS ====
$sqlTotal = "
    SELECT COUNT(*) AS total 
    FROM provedores 
    WHERE id_user = $usId and Eliminado = 0
    $whereBusqueda
";
$resultado2 = $conexion->query($sqlTotal);
if (!$resultado2) {
    die("Error SQL Total: " . $conexion->error);
    //ir a la pagina de lista_proveedores.php
    @header("Location: ./lista_proveedores.php");
}

$fila = $resultado2->fetch_assoc();
$totalProveedores = $fila['total'];

$totalPaginas = ceil($totalProveedores / $porPagina);
