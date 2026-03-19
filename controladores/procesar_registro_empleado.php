<?php
$mensaje = "";
$tipoAlerta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ===== VALIDACIONES BÁSICAS =====
    if (
        empty($_POST['nombre']) ||
        empty($_POST['apellidos']) ||
        empty($_POST['dni'])
    ) {
        $mensaje = "❌ Nombre y DNI son obligatorios.";
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
    $apellido       = trim($_POST['apellidos']);
    $dni        = trim($_POST['dni']);
    $direccion    = trim($_POST['direccion'] ?? '');
    $celular      = intval($_POST['celular'] ?? '');
    $departamento = intval($_POST['departamento'] ?? 0);
    $provincia    = trim($_POST['provincia'] ?? '');
    $distrito     = trim($_POST['distrito'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $contrasena       = trim($_POST['contrasena']);
    $Eliminado    = 0;

    /* =========================
   VALIDAR CONTRASEÑA SEGURA
========================= */
    $contrasena = $_POST['contrasena'];

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&.#_-]).+$/', $contrasena)) {
        $mensaje = "⚠️ La contraseña debe contener:
    - 1 letra mayúscula
    - 1 letra minúscula
    - 1 número
    - 1 carácter especial (@$!%*?&.#_-)";
        $tipoAlerta = "warning";
        return;
    }
    // =============================
    // VALIDAR EMAIL NO REPETIDO
    // =============================
    if (!empty($email)) {

        $sqlEmail = "SELECT id_empleado 
                 FROM empleados 
                 WHERE email = ? 
                 AND id_user = ? ";

        $stmtEmail = $conexion->prepare($sqlEmail);
        $stmtEmail->bind_param("si", $email, $usId);
        $stmtEmail->execute();
        $stmtEmail->store_result();

        if ($stmtEmail->num_rows > 0) {
            $mensaje = "❌ El correo electrónico ya está registrado para otro empleado.";
            $tipoAlerta = "danger";
            $stmtEmail->close();
            return;
        }

        $stmtEmail->close();
    }
    // =============================
    // VALIDAR DNI NO REPETIDO
    // =============================
    if (!empty($dni)) {

        $sqlDni = "SELECT id_empleado 
               FROM empleados 
               WHERE dni = ? 
               AND id_user = ?";

        $stmtDni = $conexion->prepare($sqlDni);
        $stmtDni->bind_param("si", $dni, $usId);
        $stmtDni->execute();
        $stmtDni->store_result();

        if ($stmtDni->num_rows > 0) {
            $mensaje = "❌ El DNI ya está registrado para otro empleado.";
            $tipoAlerta = "danger";
            $stmtDni->close();
            return;
        }

        $stmtDni->close();
    }
    // ===== IMAGEN POR DEFECTO (VACÍA, NO NULL) =====
    $imagen = '';
    // ===== PREPARE =====
    $sql = "INSERT INTO empleados (
                nombre,
                apellido,
                imagen,
                dni,
                celular,
                direccion,
                id_departamento,
                provincia,
                distrito,
                email,
                contrasena,
                id_user,
                estado,
                fecha_registro, id_rol
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo', NOW(), 2)";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en prepare(): " . $conexion->error);
    }

    $contrasenaHash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $stmt->bind_param(
        "ssbiisissssi",
        $nombre,          // s
        $apellido,        // s
        $imagenBinaria,
        $dni,             // i
        $celular,         // i
        $direccion,       // s
        $id_departamento, // i
        $provincia,       // s
        $distrito,        // s
        $email,           // s
        $contrasenaHash,  // s
        $usId          // i
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
        $mensaje = "✅ Empleado registrado correctamente.";
        $tipoAlerta = "success";
    } else {
        $mensaje = "❌ Error al registrar empleado: " . $stmt->error;
        $tipoAlerta = "danger";
    }

    $stmt->close();
}
