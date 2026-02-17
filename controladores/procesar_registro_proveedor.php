<?php
$mensaje = "";
$tipoAlerta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ===== VALIDACIONES BÁSICAS =====
    if (
        empty($_POST['nombre']) ||
        empty($_POST['ruc'])
    ) {
        $mensaje = "❌ Nombre y RUC son obligatorios.";
        $tipoAlerta = "danger";
        return;
    }
    // 🔴 VALIDAR DEPARTAMENTO
    /*if (
        !isset($_POST['departamento']) ||
        intval($_POST['departamento']) === 0
    ) {
        $mensaje = "❌ Debe seleccionar un departamento.";
        $tipoAlerta = "danger";
        return;
    }*/

    // ===== DATOS =====
    $usId         = $_SESSION['usId'];
    $nombre       = trim($_POST['nombre']);
    $ruc          = trim($_POST['ruc']);
    $direccion    = trim($_POST['direccion'] ?? '');
    $celular      = intval($_POST['celular'] ?? '');
    $departamento = intval($_POST['departamento'] ?? 0);
    $provincia    = trim($_POST['provincia'] ?? '');
    $distrito     = trim($_POST['distrito'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $Eliminado    = 0;

    // ===== IMAGEN POR DEFECTO (VACÍA, NO NULL) =====
    $imagen = '';

    // ===== PREPARE =====
    $sql = "INSERT INTO provedores
        (nombre, ruc, imagen, id_user, id_departamento, fecha_registro,
         distrito, provincia, direccion, email, celular, Eliminado)
        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en prepare(): " . $conexion->error);
    }

    $stmt->bind_param(
        "ssbiisssssi",
        $nombre,
        $ruc,
        $imagen,        // imagen VACÍA si no se sube archivo
        $usId,
        $departamento,
        $distrito,
        $provincia,
        $direccion,
        $email,
        $celular,
        $Eliminado
    );

    // ===== SI SE SUBE IMAGEN =====
    if (
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
        !empty($_FILES['imagen']['tmp_name'])
    ) {

        $permitidos = ['image/jpeg', 'image/png'];
        $tipoMime   = mime_content_type($_FILES['imagen']['tmp_name']);
        $tamanoMax  = 1.5 * 1024 * 1024; // 1.5MB

        if (!in_array($tipoMime, $permitidos)) {
            $mensaje = "❌ Solo se permiten imágenes JPG o PNG.";
            $tipoAlerta = "danger";
            return;
        }

        if ($_FILES['imagen']['size'] > $tamanoMax) {
            $mensaje = "❌ La imagen no debe superar 1.5 MB.";
            $tipoAlerta = "danger";
            return;
        }

        $imagenBinaria = file_get_contents($_FILES['imagen']['tmp_name']);

        // El tercer ? (índice 2) es la imagen
        $stmt->send_long_data(2, $imagenBinaria);
    }

    // ===== EJECUTAR =====
    if ($stmt->execute()) {
        $mensaje = "✅ Proveedor registrado correctamente.";
        $tipoAlerta = "success";
    } else {
        $mensaje = "❌ Error al registrar proveedor: " . $stmt->error;
        $tipoAlerta = "danger";
    }

    $stmt->close();
}
