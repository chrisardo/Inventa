<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    include 'conexion.php';

    $mensaje = "";
    $tipoAlerta = "";

    /* =========================
       VALIDAR CAMPOS OBLIGATORIOS
    ========================== */
    if (
        empty(trim($_POST['empresa'])) ||
        empty(trim($_POST['ruc'])) ||
        empty(trim($_POST['direccion'])) ||
        empty(trim($_POST['celular'])) ||
        empty(trim($_POST['username'])) ||
        empty(trim($_POST['email'])) ||
        empty(trim($_POST['contrasena'])) ||
        empty(trim($_POST['contrasena_repetir'])) ||
        !isset($_FILES['imagen']) ||
        $_FILES['imagen']['error'] !== UPLOAD_ERR_OK
    ) {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       CONTRASEÑAS COINCIDEN
    ========================== */
    if ($_POST['contrasena'] !== $_POST['contrasena_repetir']) {
        $mensaje = "⚠️ Las contraseñas no coinciden.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       CONTRASEÑA SEGURA
    ========================== */
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&._-])[A-Za-z\d@$!%*#?&._-]{8,}$/', $_POST['contrasena'])) {
        $mensaje = "⚠️ La contraseña debe tener mínimo 8 caracteres, letras, números y un carácter especial.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       VALIDAR CELULAR
    ========================== */
    if (!preg_match('/^[0-9]{9,10}$/', $_POST['celular'])) {
        $mensaje = "⚠️ El número de celular no es válido.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       VALIDAR TÉRMINOS
    ========================== */
    if (!isset($_POST['terminos'])) {
        $mensaje = "⚠️ Debe aceptar los términos y condiciones.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       VALIDAR IMAGEN (2 MB)
    ========================== */
    $imagenTmp  = $_FILES['imagen']['tmp_name'];
    $imagenSize = $_FILES['imagen']['size'];
    $imagenType = mime_content_type($imagenTmp);

    $tiposPermitidos = ['image/png', 'image/jpeg'];
    $maxSize = 1.8 * 1024 * 1024; // 1.6 MB

    if (!in_array($imagenType, $tiposPermitidos)) {
        $mensaje = "⚠️ La imagen debe ser PNG o JPG.";
        $tipoAlerta = "warning";
        return;
    }

    if ($imagenSize > $maxSize) {
        $mensaje = "⚠️ La imagen no debe superar 1.8 MB.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       VALIDAR RUC / EMAIL ÚNICOS
    ========================== */
    $stmt = $conexion->prepare(
        "SELECT id_user FROM usuario_acceso WHERE ruc = ? OR email = ?"
    );
    $stmt->bind_param("is", $_POST['ruc'], $_POST['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "⚠️ El RUC o el correo electrónico ya están registrados.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       PREPARAR DATOS
    ========================== */
    $empresa        = trim($_POST['empresa']);
    $email          = trim($_POST['email']);
    $username       = trim($_POST['username']);
    $direccion      = trim($_POST['direccion']);
    $celular        = trim($_POST['celular']);
    $ruc            = (int)$_POST['ruc'];
    $imagenBinaria  = file_get_contents($imagenTmp);
    $contrasenaHash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $null = NULL;
    $rol = 1; // Empresa


    /* =========================
       INSERTAR USUARIO (BLOB OK)
    ========================== */
    $sql = "INSERT INTO usuario_acceso (
        nombreEmpresa, email, username, contrasena, imagen,
        direccion, celular, estado, fecha_registro, ruc, password_changed_at, rol
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'activo', CURDATE(), ?, NOW(),?)";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en prepare: " . $conexion->error);
    }

    $stmt->bind_param(
        "ssssbssii",
        $empresa,
        $email,
        $username,
        $contrasenaHash,
        $null,       // IMPORTANTE PARA BLOB
        $direccion,
        $celular,
        $ruc,
        $rol
    );

    // Enviar la imagen (índice 4 empieza en 0)
    $stmt->send_long_data(4, $imagenBinaria);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $mensaje = "❌ Error al registrar: " . $stmt->error;
        $tipoAlerta = "danger";
    }

    $stmt->close();
    $conexion->close();
}
