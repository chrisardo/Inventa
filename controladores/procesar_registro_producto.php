<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'conexion.php';

$mensaje = "";
$tipoAlerta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* =========================
       VALIDAR CAMPOS OBLIGATORIOS
    ========================== */
    if (
        empty(trim($_POST['codigo'])) ||
        empty(trim($_POST['nombre'])) ||
        empty($_POST['categoria']) ||
        empty($_POST['marca']) ||
        empty($_POST['proveedor']) ||
        empty($_POST['precio_venta']) ||
        empty($_POST['precio_compra']) ||
        empty($_POST['stock'])
    ) {
        $mensaje = "⚠️ Todos los campos obligatorios deben completarse.";
        $tipoAlerta = "warning";
        return;
    }

    /* =========================
       LIMPIAR Y CONVERTIR DATOS
    ========================== */
    $codigo     = trim($_POST['codigo']);
    $nombre     = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion'] ?? '');

    $precio_venta = floatval(str_replace(',', '.', $_POST['precio_venta']));
    $precio_compra = floatval(str_replace(',', '.', $_POST['precio_compra']));
    $stock  = intval($_POST['stock']);

    $categoria = (int) $_POST['categoria'];
    $marca     = (int) $_POST['marca'];
    $proveedor = (int) $_POST['proveedor'];
    $id_user   = (int) $_SESSION['usId'];

    $id_unit_medida = 0;
    $Eliminado = 0;

    /* =========================
       VALIDACIONES NUMÉRICAS
    ========================== */
    if ($precio_venta < 0 || !is_numeric($precio_venta)) {
        $mensaje = "❌ El precio de venta ingresado no es válido.";
        $tipoAlerta = "danger";
        return;
    }
    if ($precio_compra < 0 || !is_numeric($precio_compra)) {
        $mensaje = "❌ El precio de compra ingresado no es válido.";
        $tipoAlerta = "danger";
        return;
    }

    /* =========================
    VALIDAR STOCK (ENTERO)
    ========================= */
    if (
        !ctype_digit($_POST['stock']) ||   // solo números enteros
        intval($_POST['stock']) < 0
    ) {
        $mensaje = "❌ El stock debe ser un número entero positivo.";
        $tipoAlerta = "danger";
        return;
    }

    /* =========================
       VALIDAR CATEGORÍA / MARCA / PROVEEDOR
    ========================== */
    function validarRelacion($conexion, $tabla, $campo, $id, $id_user)
    {
        $sql = "SELECT 1 FROM $tabla WHERE $campo = ? AND id_user = ? AND Eliminado = 0";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $id, $id_user);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    if (!validarRelacion($conexion, 'categorias', 'id_categorias', $categoria, $id_user)) {
        $mensaje = "❌ Categoría inválida.";
        $tipoAlerta = "danger";
        return;
    }

    if (!validarRelacion($conexion, 'marcas', 'id_marca', $marca, $id_user)) {
        $mensaje = "❌ Marca inválida.";
        $tipoAlerta = "danger";
        return;
    }

    if (!validarRelacion($conexion, 'provedores', 'id_provedor', $proveedor, $id_user)) {
        $mensaje = "❌ Proveedor inválido.";
        $tipoAlerta = "danger";
        return;
    }

    /* =========================
       CONTROLAR DUPLICADOS (CÓDIGO)
    ========================== */
    $stmt = $conexion->prepare(
        "SELECT 1 FROM producto 
         WHERE codigo = ? AND id_user = ? AND Eliminado = 0"
    );
    $stmt->bind_param("si", $codigo, $id_user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "⚠️ Ya existe un producto con ese código.";
        $tipoAlerta = "warning";
        $stmt->close();
        return;
    }
    $stmt->close();

    /* =========================
       VALIDAR IMAGEN (OPCIONAL)
    ========================== */
    $imagenBinaria = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $mensaje = "❌ Error al subir la imagen.";
            $tipoAlerta = "danger";
            return;
        }

        if ($_FILES['imagen']['size'] > (1.8 * 1024 * 1024)) {
            $mensaje = "❌ La imagen no puede superar 1.8 MB.";
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

    /* =========================
       INSERTAR PRODUCTO
    ========================== */
    $sql = "INSERT INTO producto (
                codigo, nombre, imagen, precio, stock,
                id_unit_medida, id_user, id_categorias,
                fecha_registro, descripcion, id_provedor,
                Eliminado, id_marca, costo_compra
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "ssbdiiiisiiid",
        $codigo,
        $nombre,
        $imagenBinaria,
        $precio_venta,
        $stock,
        $id_unit_medida,
        $id_user,
        $categoria,
        $descripcion,
        $proveedor,
        $Eliminado,
        $marca,
        $precio_compra
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
}
