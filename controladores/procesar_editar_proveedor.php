<?php
session_start();
include 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usId'])) {
    echo json_encode([
        'tipo' => 'danger',
        'mensaje' => 'Sesión no válida.'
    ]);
    exit;
}

function responder($tipo, $mensaje)
{
    echo json_encode([
        'tipo' => $tipo,
        'mensaje' => $mensaje
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['accion'] ?? '') !== 'editar') {
    responder('danger', 'Acción no permitida.');
}

$id   = intval($_POST['id_proveedor'] ?? 0);
$usId = intval($_SESSION['usId']);

$nombre       = trim($_POST['nombre'] ?? '');
$ruc          = trim($_POST['dni_o_ruc'] ?? '');
$celular      = trim($_POST['celular'] ?? '');
$email        = trim($_POST['email'] ?? '');
$direccion    = trim($_POST['direccion'] ?? '');
$provincia    = trim($_POST['provincia'] ?? '');
$distrito     = trim($_POST['distrito'] ?? '');
$departamento = intval($_POST['departamento'] ?? 0);

/* ========= VALIDACIONES ========= */
if ($nombre === '') {
    responder('danger', 'El nombre es obligatorio.');
}

if ($ruc === '') {
    responder('danger', 'El documento es obligatorio.');
}

if ($celular === '') {
    responder('danger', 'El celular es obligatorio.');
}

if ($departamento === 0) {
    responder('danger', 'Debe seleccionar un departamento.');
}

/* ========= VALIDAR IMAGEN ========= */
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
        responder('danger', 'Solo se permiten imágenes JPG o PNG.');
    }

    if ($_FILES['imagen']['size'] > $tamanoMax) {
        responder('danger', 'La imagen no debe superar 1.5 MB.');
    }
}

/* ========= UPDATE ========= */
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

if ($stmt->execute()) {
    responder('success', 'Proveedor actualizado correctamente.');
} else {
    responder('danger', 'Error al actualizar proveedor.');
}
