<?php
session_start();
include "../../controladores/conexion.php";

if (!isset($_SESSION['id_empleado'])) {
    exit("Acceso no autorizado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usId = $_SESSION['id_empleado'];
    $contrasenaActual = trim($_POST['contrasenaActual']);
    $contrasenaNueva = trim($_POST['contrasenaNueva']);
    $contrasenaConfirmar = trim($_POST['contrasenaConfirmar']);

    $errors = [];

    // Validaciones básicas
    if (empty($contrasenaActual) || empty($contrasenaNueva) || empty($contrasenaConfirmar)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    if ($contrasenaNueva !== $contrasenaConfirmar) {
        $errors[] = "La nueva contraseña y su confirmación no coinciden.";
    }
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&._-])[A-Za-z\d@$!%*#?&._-]{8,}$/', $_POST['contrasenaNueva'])) {
        $errors[] = "⚠️ La nueva contraseña debe tener mínimo 8 caracteres, letras, números y un carácter especial.";
    }

    // Obtener hash actual
    $sql = "SELECT contrasena FROM empleados WHERE id_empleado = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $errors[] = "Usuario no encontrado.";
    }

    // 🔐 VERIFICAR CONTRASEÑA ACTUAL (CORRECTO)
    if (!password_verify($contrasenaActual, $user['contrasena'])) {
        $errors[] = "La contraseña actual es incorrecta.";
    }
    // 🚫 VALIDAR CONTRASEÑA DIFERENTE
    if (password_verify($contrasenaNueva, $user['contrasena'])) {
        $errors[] = "La nueva contraseña no puede ser igual a la contraseña actual.";
    }
    if (!empty($errors)) {
        echo '<div class="alert alert-danger">';
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
        exit();
    }

    // 🔐 HASHEAR NUEVA CONTRASEÑA
    $nuevoHash = password_hash($contrasenaNueva, PASSWORD_DEFAULT);

    // Actualizar contraseña
    $sqlUpdate = "UPDATE empleados SET contrasena = ? WHERE id_empleado = ?";
    $stmtUpdate = $conexion->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $nuevoHash, $usId);

    if ($stmtUpdate->execute()) {
        echo '<div class="alert alert-success">✅ Contraseña actualizada correctamente.</div>';
    } else {
        echo '<div class="alert alert-danger">❌ Error al actualizar la contraseña.</div>';
    }
}
