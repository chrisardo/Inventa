<?php
session_start();
include "../../controladores/conexion.php";

header('Content-Type: application/json');

if (!isset($_SESSION['id_empleado'])) {
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

$usId = (int)$_POST['id_empleado'];
$nombreEmpresa = trim($_POST['nombre']);
$apellidos = trim($_POST['apellidos']);
$celular = trim($_POST['celular']);
$direccion = trim($_POST['direccion']);

$errors = [];

if ($nombreEmpresa === '') $errors[] = "El nombre es obligatorio";
if ($apellidos === '') $errors[] = "El apellido es obligatorio";
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

    $sql = "UPDATE empleados
            SET nombre=?, apellido=?, celular=?, direccion=?, imagen=?
            WHERE id_empleado=?";

    $stmt = $conexion->prepare($sql);
    $null = null;

    $stmt->bind_param("ssssbi", $nombreEmpresa, $apellidos,$celular, $direccion, $null, $usId);
    $stmt->send_long_data(4, $imagenData);
} else {

    $sql = "UPDATE empleados
            SET nombre=?, apellido=?, celular=?, direccion=?
            WHERE id_empleado=?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $nombreEmpresa, $apellidos, $celular, $direccion, $usId);
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
