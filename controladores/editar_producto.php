<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usId'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

$mensaje = "";
$tipoAlerta = "danger"; // success | danger | warning

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar') {

    $usId       = intval($_SESSION['usId']);
    $idProducto = intval($_POST['idProducto'] ?? 0);

    /* ================= VALIDACIONES ================= */

    $camposObligatorios = [
        'nombre'     => 'Nombre',
        'codigo'     => 'SKU',
        'precio'     => 'Precio',
        'costo'      => 'Costo de compra',
        'stock'      => 'Stock',
        'categoria'  => 'Categoría',
        'sucursal'  => 'Sucursal',
        'marca'      => 'Marca'
    ];

    foreach ($camposObligatorios as $campo => $label) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
            $mensaje = "El campo <strong>{$label}</strong> es obligatorio.";
            return;
        }
    }

    if (!is_numeric($_POST['precio']) || $_POST['precio'] <= 0) {
        $mensaje = "El precio debe ser mayor a 0.";
        return;
    }

    if (!is_numeric($_POST['costo']) || $_POST['costo'] < 0) {
        $mensaje = "El costo de compra no puede ser negativo.";
        return;
    }

    if (!ctype_digit($_POST['stock'])) {
        $mensaje = "El stock debe ser un número entero.";
        return;
    }

    /* ================= DATOS ================= */

    $nombre       = trim($_POST['nombre']);
    $codigo       = trim($_POST['codigo']);
    $precio       = floatval($_POST['precio']);
    $costo        = floatval($_POST['costo']);
    $stock        = intval($_POST['stock']);
    $descripcion  = trim($_POST['descripcion'] ?? '');
    $idSucursal  = intval($_POST['sucursal']);
    $idProveedor = !empty($_POST['provedor']) ? intval($_POST['provedor']) : null;
    $idCategoria  = intval($_POST['categoria']);
    $idMarca      = intval($_POST['marca']);

    /* ================= IMAGEN ================= */

    $hayImagen = (
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES['imagen']['tmp_name'])
    );

    if ($hayImagen) {
        $permitidos = ['image/jpeg', 'image/png'];
        $mime       = mime_content_type($_FILES['imagen']['tmp_name']);
        $maxSize    = 1.8 * 1024 * 1024;

        if (!in_array($mime, $permitidos)) {
            $mensaje = "Solo se permiten imágenes JPG o PNG.";
            return;
        }

        if ($_FILES['imagen']['size'] > $maxSize) {
            $mensaje = "La imagen no debe superar 1.8 MB.";
            return;
        }
    }

    /* ================= SQL ================= */

    if (!$hayImagen) {

        $sql = "UPDATE producto SET
                    nombre = ?, codigo = ?, precio = ?, costo_compra = ?,
                    stock = ?, descripcion = ?, id_provedor = ?, id_categorias = ?, id_marca = ?, id_sucursal = ?
                WHERE idProducto = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "ssddisiiiiii",
            $nombre,
            $codigo,
            $precio,
            $costo,
            $stock,
            $descripcion,
            $idProveedor,
            $idCategoria,
            $idMarca,
            $idSucursal,
            $idProducto,
            $usId
        );
    } else {

        $sql = "UPDATE producto SET
                    nombre = ?, codigo = ?, precio = ?, costo_compra = ?,
                    imagen = ?, stock = ?, descripcion = ?,
                    id_provedor = ?, id_categorias = ?, id_marca = ?, id_sucursal = ?
                WHERE idProducto = ? AND id_user = ?";

        $stmt = $conexion->prepare($sql);
        $imagen = null;

        $stmt->bind_param(
            "ssddbisiiiiii",
            $nombre,
            $codigo,
            $precio,
            $costo,
            $imagen,
            $stock,
            $descripcion,
            $idProveedor,
            $idCategoria,
            $idMarca,
            $idSucursal,
            $idProducto,
            $usId
        );

        $stmt->send_long_data(4, file_get_contents($_FILES['imagen']['tmp_name']));
    }

    /* ================= EJECUTAR ================= */

    if ($stmt->execute()) {
        $_SESSION['mensajeProducto'] = "Producto actualizado correctamente.";
        $_SESSION['tipoProducto'] = "success";
        header("Location: lista_productos.php");
        exit();
    } else {
        $mensaje = "Error al actualizar el producto.";
    }
}
