<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    include 'conexion.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {

        // Buscar usuario
        $stmt = $conexion->prepare("
            SELECT id_user, username, email, contrasena, nombreEmpresa
            FROM usuario_acceso
            WHERE username = ? OR email = ?
        ");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $row = $result->fetch_assoc();

            // 🔐 Verificar contraseña
            if (password_verify($password, $row['contrasena'])) {
                $_SESSION['usId'] = $row['id_user'];

                header("Location: index.php");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }

        } else {
            $error = "Usuario o contraseña incorrectos.";
        }

        $stmt->close();
    }

    $conn->close();
}
