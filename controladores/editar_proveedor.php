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
    $id   = intval($_POST['id_proveedor'] ?? 0);

    /* ================= VALIDACIONES ================= */

    if (
        empty(trim($_POST['nombre'] ?? '')) ||
        empty(trim($_POST['dni_o_ruc'] ?? '')) ||
        empty(trim($_POST['celular'] ?? ''))
    ) {
        $mensaje = "Nombre, documento y celular son obligatorios.";
        return;
    }

    if (empty($_POST['departamento']) || intval($_POST['departamento']) === 0) {
        $mensaje = "Debe seleccionar un departamento.";
        return;
    }

    /* ================= DATOS ================= */

    $nombre       = trim($_POST['nombre']);
    $ruc          = trim($_POST['dni_o_ruc']);
    $celular      = trim($_POST['celular']);
    $email        = trim($_POST['email'] ?? '');
    $direccion    = trim($_POST['direccion'] ?? '');
    $provincia    = trim($_POST['provincia'] ?? '');
    $distrito     = trim($_POST['distrito'] ?? '');
    $departamento = intval($_POST['departamento']);

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

        $sql = "UPDATE provedores SET
                    nombre = ?, ruc = ?, celular = ?, email = ?, direccion = ?,
                    provincia = ?, distrito = ?, id_departamento = ?
                WHERE id_provedor = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);

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
    } else {

        $sql = "UPDATE provedores SET
                    nombre = ?, ruc = ?, imagen = ?, celular = ?, email = ?,
                    direccion = ?, provincia = ?, distrito = ?, id_departamento = ?
                WHERE id_provedor = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);
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

        $stmt->send_long_data(2, file_get_contents($_FILES['imagen']['tmp_name']));
    }

    /* ================= EJECUTAR ================= */

    if ($stmt->execute()) {
        $_SESSION['mensajeProveedor'] = "Actualizado correctamente.";
        $_SESSION['tipoProveedor'] = "success";
        header("Location: lista_proveedores.php");
        exit();
    } else {
        $mensaje = "Error al actualizar el producto.";
    }
}
