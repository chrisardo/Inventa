<?php
$mensaje = "";
$tipoAlerta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !empty(trim($_POST['codigo'])) &&
        !empty(trim($_POST['nombre'])) &&
        !empty($_POST['categoria']) &&
        !empty($_POST['proveedor']) &&
        !empty($_POST['marca'])
    ) {

        $codigo     = trim($_POST['codigo']);
        $nombre     = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $precio = str_replace(',', '.', $_POST['precio']);
        $precio = floatval($precio);

        $stock      = intval($_POST['stock']);
        $categoria  = intval($_POST['categoria']);
        $proveedor  = intval($_POST['proveedor']);
        $marca      = intval($_POST['marca']);
        $id_user    = $_SESSION['usId'];
        $id_unit_medida = 0;
        $Eliminado  = 0;

        if ($precio < 0 || $stock < 0) {
            $mensaje = "❌ Precio y stock no pueden ser negativos.";
            $tipoAlerta = "danger";
            return;
        }
        if (!is_numeric($precio)) {
            $mensaje = "❌ El precio ingresado no es válido.";
            $tipoAlerta = "danger";
            return;
        }

        // ------------------------
        // VALIDACIÓN DE IMAGEN
        // ------------------------
        $imagenBinaria = '';

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {

            if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                $mensaje = "❌ Error al subir la imagen.";
                $tipoAlerta = "danger";
                return;
            }

            if ($_FILES['imagen']['size'] > (1.5 * 1024 * 1024)) {
                $mensaje = "❌ La imagen no puede superar 1.5 MB.";
                $tipoAlerta = "danger";
                return;
            }

            $mime = mime_content_type($_FILES['imagen']['tmp_name']);
            if (!in_array($mime, ['image/png', 'image/jpeg'])) {
                $mensaje = "❌ Solo se permiten imágenes PNG o JPG.";
                $tipoAlerta = "danger";
                return;
            }

            $imagenBinaria = file_get_contents($_FILES['imagen']['tmp_name']);
        }

        // ------------------------
        // INSERT CON PREPARED
        // ------------------------
        $sql = "INSERT INTO producto 
            (codigo, nombre, imagen, precio, stock, id_unit_medida, id_user, id_categorias, fecha_registro, descripcion, id_provedor, Eliminado, id_marca)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "ssbdiiiisiii",
            $codigo,
            $nombre,
            $imagenBinaria,
            $precio,
            $stock,
            $id_unit_medida,
            $id_user,
            $categoria,
            $descripcion,
            $proveedor,
            $Eliminado,
            $marca
        );

        if ($imagenBinaria !== null) {
            $stmt->send_long_data(2, $imagenBinaria);
        }

        if ($stmt->execute()) {
            $mensaje = $imagenBinaria
                ? "✅ Producto registrado correctamente con imagen."
                : "✅ Producto registrado correctamente sin imagen.";
            $tipoAlerta = "success";
        } else {
            $mensaje = "❌ Error al registrar producto: " . $stmt->error;
            $tipoAlerta = "danger";
        }

        $stmt->close();
    } else {
        $mensaje = "⚠️ Todos los campos obligatorios deben completarse.";
        $tipoAlerta = "warning";
    }
}
