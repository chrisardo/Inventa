<?php
$mensaje = "";
$tipoAlerta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* ===== VALIDACIONES BÁSICAS ===== */
    if (empty($_POST['nombre']) || empty($_POST['ruc']) || empty($_POST['celular'])) {
        $mensaje = "❌ Nombre, RUC y celular son obligatorios.";
        $tipoAlerta = "danger";
        return;
    }

    if (empty($_POST['departamento']) || intval($_POST['departamento']) === 0) {
        $mensaje = "❌ Debe seleccionar un departamento.";
        $tipoAlerta = "danger";
        return;
    }

    if (empty($_POST['rubro']) || intval($_POST['rubro']) === 0) {
        $mensaje = "❌ Debe seleccionar un rubro.";
        $tipoAlerta = "danger";
        return;
    }

    /* ===== DATOS ===== */
    $usId         = intval($_SESSION['usId']);
    $nombre       = trim($_POST['nombre']);
    $ruc          = trim($_POST['ruc']);
    $direccion    = trim($_POST['direccion'] ?? '');
    $celular      = intval($_POST['celular']);
    $departamento = intval($_POST['departamento']);
    $provincia    = trim($_POST['provincia'] ?? '');
    $distrito     = trim($_POST['distrito'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $id_rubro     = intval($_POST['rubro']);
    $Eliminado    = 0;

    /* ===== IMAGEN ===== */
    //$imagen = null; // ← IMPORTANTE: NULL REAL
     $imagen = '';

    /* ===== SQL ===== */
    $sql = "INSERT INTO clientes
        (nombre, dni_o_ruc, imagen, id_user, id_departamento, fecha_registro,
         distrito, provincia, direccion, email, celular, id_rubro, Eliminado)
        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        die("Error prepare(): " . $conexion->error);
    }

    /* ===== BIND ===== */
    $stmt->bind_param(
        "ssbiissssiii",
        $nombre,
        $ruc,
        $imagen,
        $usId,
        $departamento,
        $distrito,
        $provincia,
        $direccion,
        $email,
        $celular,
        $id_rubro,
        $Eliminado
    );


    /* ===== SI HAY IMAGEN ===== */
    if (
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES['imagen']['tmp_name'])
    ) {

        $permitidos = ['image/jpeg', 'image/png'];
        $tipoMime   = mime_content_type($_FILES['imagen']['tmp_name']);
        $tamanoMax  = 1.8 * 1024 * 1024;

        if (!in_array($tipoMime, $permitidos)) {
            $mensaje = "❌ Solo se permiten imágenes JPG o PNG.";
            $tipoAlerta = "danger";
            return;
        }

        if ($_FILES['imagen']['size'] > $tamanoMax) {
            $mensaje = "❌ La imagen no debe superar 1.8 MB.";
            $tipoAlerta = "danger";
            return;
        }

        $imagenBinaria = file_get_contents($_FILES['imagen']['tmp_name']);
        $stmt->send_long_data(2, $imagenBinaria); // índice 2 = imagen
    }

    /* ===== EJECUTAR ===== */
    if ($stmt->execute()) {
        $mensaje = "✅ Cliente registrado correctamente.";
        $tipoAlerta = "success";
    } else {
        $mensaje = "❌ Error al registrar cliente: " . $stmt->error;
        $tipoAlerta = "danger";
    }

    $stmt->close();
}
