<?php
session_start();
include "conexion.php";

header('Content-Type: application/json');

if (!isset($_SESSION['usId'])) {
    echo json_encode([
        "status" => "error",
        "messages" => ["Sesión no válida"]
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "messages" => ["Método inválido"]
    ]);
    exit;
}

$usId = (int)$_POST['usId'];
$nombreEmpresa = trim($_POST['nombreEmpresa']);
$celular = trim($_POST['celular']);
$direccion = trim($_POST['direccion']);

$errors = [];

if ($nombreEmpresa === '') $errors[] = "El nombre de la empresa es obligatorio";
if ($celular === '' || !is_numeric($celular)) $errors[] = "Celular inválido";
if ($direccion === '') $errors[] = "La dirección es obligatoria";

/* ---------- IMAGEN ---------- */
$hayImagen = false;
$imagenData = null;

if (!empty($_FILES['imagen']['tmp_name'])) {

    $tmp  = $_FILES['imagen']['tmp_name'];
    $size = $_FILES['imagen']['size'];
    $mime = mime_content_type($tmp);
    $ext  = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

    $mimePermitidos = ['image/png', 'image/jpeg'];
    $extPermitidas  = ['png', 'jpg', 'jpeg'];
    //$maxSize = 2 * 1024 * 1024;
    $maxSize = 1781760; // 1.7 MB
    if (!in_array($mime, $mimePermitidos)) {
        $errors[] = "La imagen debe ser PNG o JPG";
    } elseif (!in_array($ext, $extPermitidas)) {
        $errors[] = "Extensión no permitida";
    } elseif ($size > $maxSize) {
        $errors[] = "La imagen no debe superar 1.6 MB";
    } else {
        $imagenData = file_get_contents($tmp);
        $hayImagen = true;
    }
}

if (!empty($errors)) {
    echo json_encode([
        "status" => "error",
        "messages" => $errors
    ]);
    exit;
}

/* ---------- UPDATE ---------- */
if ($hayImagen) {

    $sql = "UPDATE usuario_acceso
            SET nombreEmpresa=?, celular=?, direccion=?, imagen=?
            WHERE id_user=?";

    $stmt = $conexion->prepare($sql);
    $null = null;

    $stmt->bind_param("sssbi", $nombreEmpresa, $celular, $direccion, $null, $usId);
    $stmt->send_long_data(3, $imagenData);
} else {

    $sql = "UPDATE usuario_acceso
            SET nombreEmpresa=?, celular=?, direccion=?
            WHERE id_user=?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombreEmpresa, $celular, $direccion, $usId);
}

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "messages" => ["Perfil actualizado correctamente"]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "messages" => ["Error SQL: " . $stmt->error]
    ]);
}
