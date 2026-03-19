<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once 'conexion.php';

    $usuario = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($usuario) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {

        // =====================================================
        // 1️⃣ BUSCAR EN usuario_acceso (ADMINISTRADOR)
        // =====================================================
        $stmt = $conexion->prepare("
            SELECT id_user, username, email, contrasena, estado, rol
            FROM usuario_acceso
            WHERE username = ? OR email = ?
            LIMIT 1
        ");

        $stmt->bind_param("ss", $usuario, $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $row = $result->fetch_assoc();

            if (password_verify($password, $row['contrasena'])) {

                if (strtolower($row['estado']) !== 'activo') {
                    $error = "Tu cuenta está inactiva.";
                } else {

                    $_SESSION['usId'] = $row['id_user'];
                    $_SESSION['rol'] = $row['rol'];
                    $_SESSION['tipo'] = 'admin';

                    if ($row['rol'] == 1) {
                        header("Location: index.php");
                        exit();
                    }
                }

            } else {
                $error = "Usuario o contraseña incorrectos.";
            }

        } else {

            // =====================================================
            // 2️⃣ BUSCAR EN empleados
            // =====================================================
            $stmt2 = $conexion->prepare("
                SELECT id_empleado, id_user, email, contrasena, estado, id_rol
                FROM empleados
                WHERE email = ?
                LIMIT 1
            ");

            $stmt2->bind_param("s", $usuario);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows === 1) {

                $row2 = $result2->fetch_assoc();

                if (password_verify($password, $row2['contrasena'])) {

                    if (strtolower($row2['estado']) !== 'activo') {
                        $error = "Tu cuenta está inactiva.";
                    } else {

                        $_SESSION['usId'] = $row2['id_user']; // empresa
                        $_SESSION['id_empleado'] = $row2['id_empleado'];
                        $_SESSION['rol'] = $row2['id_rol'];
                        $_SESSION['tipo'] = 'empleado';

                        if ($row2['id_rol'] == 2) {
                            header("Location: empleado/index.php");
                            exit();
                        }
                    }

                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }

            } else {
                $error = "Usuario o contraseña incorrectos.";
            }

            $stmt2->close();
        }

        $stmt->close();
    }

    $conexion->close();
}
?>