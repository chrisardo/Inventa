<?php
//Toda esta parte es de controladores/editar_proveedor.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usId'])) {
    $mensaje = "Sesión no válida.";
    return;
}
require_once 'conexion.php';

$mensaje = "";
$tipoAlerta = "danger";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar') {
    $usId = intval($_SESSION['usId']);
    $id   = intval($_POST['id_empleado'] ?? 0);

    /* ================= VALIDACIONES ================= */

    if (
        empty(trim($_POST['nombre'] ?? '')) ||
        empty(trim($_POST['dni_o_ruc'] ?? ''))
    ) {
        $mensaje = "Nombre y documento son obligatorios.";
        return;
    }

    /*if (empty($_POST['departamento']) || intval($_POST['departamento']) === 0) {
        $mensaje = "Debe seleccionar un departamento.";
        return;
    }*/

    /* ================= DATOS ================= */

    $nombre       = trim($_POST['nombre']);
    $apellido       = trim($_POST['apellido']);
    $dni          = trim($_POST['dni_o_ruc']);
    $celular      = trim($_POST['celular']);
    $estado      = trim($_POST['estado']);
    $email        = trim($_POST['email'] ?? '');
    $direccion    = trim($_POST['direccion'] ?? '');
    $contrasena    = trim($_POST['contrasena'] ?? '');
    $provincia    = trim($_POST['provincia'] ?? '');
    $distrito     = trim($_POST['distrito'] ?? '');
    $departamento = intval($_POST['departamento']);

    // Encriptar contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    /* ================= IMAGEN ================= */

    $hayImagen = (
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES['imagen']['tmp_name'])
    );

    if ($hayImagen) {

        $permitidos = ['image/jpeg', 'image/png'];
        $mime       = mime_content_type($_FILES['imagen']['tmp_name']);
        $maxSize    = 1.8 * 1024 * 1024;

        if (!in_array($mime, $permitidos)) {
            $mensaje = "Solo se permiten imágenes JPG o PNG.";
            return;
        }

        if ($_FILES['imagen']['size'] > $maxSize) {
            $mensaje = "La imagen no debe superar 1.8 MB.";
            return;
        }
    }

    /* ================= SQL ================= */

    if (!$hayImagen) {

        $sql = "UPDATE empleados SET
                    nombre = ?,
                    apellido = ?,
                    dni = ?,
                    celular = ?,
                    email = ?,
                    direccion = ?,
                    provincia = ?,
                    distrito = ?,
                    id_departamento = ?,
                    estado = ?, contrasena = ?
                WHERE id_empleado = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "sssssssssssii",
            $nombre,
            $apellido,
            $dni,
            $celular,
            $email,
            $direccion,
            $provincia,
            $distrito,
            $departamento,
            $estado,
            $contrasena_hash,
            $id,
            $usId
        );
    } else {

        $sql = "UPDATE empleados SET
                    imagen = ?,
                    nombre = ?,
                    apellido = ?,
                    dni = ?,
                    celular = ?,
                    email = ?,
                    direccion = ?,
                    provincia = ?,
                    distrito = ?,
                    id_departamento = ?,
                    estado = ?, contrasena = ?
                WHERE id_empleado = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);
        $imagen = null;

        $stmt->bind_param(
            "bsssssssssssii",
            $imagen,
            $nombre,
            $apellido,
            $dni,
            $celular,
            $email,
            $direccion,
            $provincia,
            $distrito,
            $departamento,
            $estado,
            $contrasena_hash,
            $id,
            $usId
        );

        $stmt->send_long_data(0, file_get_contents($_FILES['imagen']['tmp_name']));
    }

    /* ================= EJECUTAR ================= */

    if ($stmt->execute()) {
        $_SESSION['mensajeProveedor'] = "Actualizado correctamente.";
        $_SESSION['tipoProveedor'] = "success";
        header("Location: lista_empleados.php");
        exit();
    } else {
        $mensaje = "Error al actualizar el producto.";
    }
}
