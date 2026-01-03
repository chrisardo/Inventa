<?php
//seccion de controladores/procesar_lista_productos.php
// Conexión
//include 'conexion.php';
$usId = $_SESSION['usId'];
$mensaje = "";
$tipoAlerta = "";
$tipoMensaje = ""; // success, danger, warning, info

// ==== PROCESAR EDICIÓN y GUARDAR CAMBIOS EN LA BASE DE DATOS, PERO PRIMERO VALIDAR QUE EL NOMBRE, CELULAR, DOCUMENTO NO ESTEN VACIOS ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar') {

    header('Content-Type: application/json');

    // ===== CAMPOS OBLIGATORIOS =====
    $requeridos = ['nombre', 'codigo', 'precio', 'costo', 'stock', 'categoria', 'marca', 'provedor'];

    foreach ($requeridos as $campo) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
            echo json_encode([
                "tipo" => "danger",
                "mensaje" => "Todos los campos son obligatorios."
            ]);
            exit();
        }
    }

    // ===== STOCK ENTERO =====
    if (!ctype_digit($_POST['stock'])) {
        echo json_encode([
            "tipo" => "danger",
            "mensaje" => "El stock debe ser un número entero sin decimales."
        ]);
        exit();
    }

    // ===== VALIDAR IMAGEN =====
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        $max = 1.8 * 1024 * 1024;
        $mime = mime_content_type($_FILES['imagen']['tmp_name']);

        if ($_FILES['imagen']['size'] > $max) {
            echo json_encode([
                "tipo" => "danger",
                "mensaje" => "La imagen supera los 1.8 MB."
            ]);
            exit();
        }

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            echo json_encode([
                "tipo" => "danger",
                "mensaje" => "Solo se permiten imágenes JPG o PNG."
            ]);
            exit();
        }

        $imagenData = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    // ===== LIMPIAR =====
    $idProducto = (int)$_POST['idProducto'];
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $codigo = $conexion->real_escape_string($_POST['codigo']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio']);
    $costo = floatval($_POST['costo']);
    $categoria = (int)$_POST['categoria'];
    $proveedor = (int)$_POST['provedor'];
    $marca = (int)$_POST['marca'];
    $stock = (int)$_POST['stock'];
    $usId = $_SESSION['usId'];

    // ===== UPDATE =====
    if (isset($imagenData)) {

        $stmt = $conexion->prepare("
            UPDATE producto SET
                nombre=?, descripcion=?, precio=?, codigo=?, stock=?, imagen=?,
                id_categorias=?, id_provedor=?, id_marca=?, costo_compra=?
            WHERE idProducto=? AND id_user=?
        ");

        $null = NULL;

        $stmt->bind_param(
            "ssdsiibiiiii",
            $nombre,
            $descripcion,
            $precio,
            $codigo,
            $stock,
            $null,
            $categoria,
            $proveedor,
            $marca,
            $costo,
            $idProducto,
            $usId
        );

        $stmt->send_long_data(5, $imagenData);
        $ok = $stmt->execute();
    } else {

        $ok = $conexion->query("
            UPDATE producto SET
                nombre='$nombre',
                descripcion='$descripcion',
                precio=$precio,
                codigo='$codigo',
                stock=$stock,
                id_categorias=$categoria,
                id_provedor=$proveedor,
                id_marca=$marca,
                costo_compra=$costo
            WHERE idProducto=$idProducto AND id_user=$usId
        ");
    }

    echo json_encode([
        "tipo" => $ok ? "success" : "danger",
        "mensaje" => $ok
            ? "Producto actualizado correctamente."
            : "Error al actualizar el producto."
    ]);
    exit();
}


// ==== ELIMINAR ====
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("UPDATE producto SET Eliminado = 1 WHERE idProducto = $id  AND id_user = $usId");
    //$conexion->query("DELETE FROM producto WHERE idProducto = $id AND id_user = $id_user");
    //ir a la pagina de lista_productos.php
    @header("Location: ./lista_productos.php");
    exit();
}


// ==== PAGINACIÓN ====
$porPagina = 5; // cantidad de productos por página
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $porPagina;
// ==== CONSULTAS PARA MOSTRAR EN LA TABLA ====

// ==== BUSQUEDA ====
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($busqueda !== '') {

    $busquedaEsc = $conexion->real_escape_string($busqueda);

    $sql = "
            SELECT p.*, pr.nombre AS nombre_proveedor, m.nombre AS nombre_marca
            FROM producto p
            LEFT JOIN provedores pr
                ON p.id_provedor = pr.id_provedor AND pr.Eliminado = 0
            LEFT JOIN marcas m 
                ON p.id_marca = m.id_marca AND m.Eliminado = 0
            WHERE p.id_user = $usId AND p.Eliminado = 0
            AND (
                p.nombre LIKE '%$busquedaEsc%'
                OR p.codigo LIKE '%$busquedaEsc%'
                OR p.fecha_registro LIKE '%$busquedaEsc%'
            )
            ORDER BY p.idProducto DESC
            LIMIT $inicio, $porPagina

    ";

    $resultado = $conexion->query($sql);

    $sqlTotal = "
        SELECT COUNT(*) AS total
        FROM producto
        WHERE id_user = $usId and Eliminado = 0
        AND (
            nombre LIKE '%$busquedaEsc%'
            OR codigo LIKE '%$busquedaEsc%'
            OR fecha_registro LIKE '%$busquedaEsc%'
        )
    ";

    $resultado2 = $conexion->query($sqlTotal);
} else {

    $resultado = $conexion->query("
        SELECT p.*, 
        pr.nombre AS nombre_proveedor,  m.nombre AS nombre_marca
        FROM producto p
        LEFT JOIN provedores pr
            ON p.id_provedor = pr.id_provedor AND pr.Eliminado = 0
        LEFT JOIN marcas m 
            ON p.id_marca = m.id_marca AND m.Eliminado = 0
        WHERE p.id_user = $usId AND p.Eliminado = 0
        ORDER BY p.idProducto DESC
        LIMIT $inicio, $porPagina

    ");

    $resultado2 = $conexion->query("
        SELECT COUNT(*) AS total 
        FROM producto
        WHERE id_user = $usId and Eliminado = 0
    ");
}

// ==== TOTALES ====
$fila = $resultado2->fetch_assoc();
$totalProducto = $fila['total'];
$totalPaginas = ceil($totalProducto / $porPagina);
