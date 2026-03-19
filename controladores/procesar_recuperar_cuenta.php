<?php
include "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST["busqueda"])) {
        $mensaje = "Ingrese un correo o celular.";
        return;
    }

    $busqueda = trim($_POST["busqueda"]);

    /* =====================================================
       1️⃣ BUSCAR EN usuario_acceso (ADMIN)
    ===================================================== */

    $sqlAdmin = "SELECT id_user
                 FROM usuario_acceso
                 WHERE email = ? OR celular = ?
                 LIMIT 1";

    $stmtAdmin = $conexion->prepare($sqlAdmin);
    $stmtAdmin->bind_param("ss", $busqueda, $busqueda);
    $stmtAdmin->execute();
    $resultadoAdmin = $stmtAdmin->get_result();

    if ($resultadoAdmin->num_rows === 1) {

        $admin = $resultadoAdmin->fetch_assoc();

        header("Location: ./restablecer_contrasena.php?uid=" . $admin["id_user"] .
            "&tipo=admin");
        exit;
    }

    /* =====================================================
       2️⃣ BUSCAR EN empleados
    ===================================================== */

    $sqlEmpleado = "SELECT id_empleado, id_user
                    FROM empleados
                    WHERE email = ? OR celular = ?
                    LIMIT 1";

    $stmtEmpleado = $conexion->prepare($sqlEmpleado);
    $stmtEmpleado->bind_param("ss", $busqueda, $busqueda);
    $stmtEmpleado->execute();
    $resultadoEmpleado = $stmtEmpleado->get_result();

    if ($resultadoEmpleado->num_rows === 1) {

        $empleado = $resultadoEmpleado->fetch_assoc();

        header("Location: ./restablecer_contrasena.php?uid=" . $empleado["id_empleado"] .
            "&tipo=empleado");
        exit;
    }

    /* =====================================================
       3️⃣ SI NO EXISTE EN NINGUNA TABLA
    ===================================================== */

    $mensaje = "No se encontró ninguna cuenta con ese correo o celular.";

    $stmtAdmin->close();
    $stmtEmpleado->close();
    $conexion->close();
}
