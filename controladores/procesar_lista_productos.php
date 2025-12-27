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
    // Validaciones obligatorias menos descripcion, el stock debe ser 0 o mayor a 0, el precio debe ser 0 o mayor a 0
    if (
        !empty(trim($_POST['codigo']))  &&
        !empty(trim($_POST['nombre']))  &&
        floatval($_POST['precio']) >= 0 &&
        !empty(trim($_POST['categoria'])) &&
        !empty(trim($_POST['marca'])) &&
        !empty(trim($_POST['provedor'])) &&
        intval($_POST['stock']) >= 0
    ) {
        // Todos los campos obligatorios están llenos menos descripcion

        $nombre = $conexion->real_escape_string(trim($_POST['nombre']));
        $descripcion = $conexion->real_escape_string(trim($_POST['descripcion']));
        $precio = str_replace(',', '.', $_POST['precio']);
        $precio = floatval($precio);
        $codigo = $conexion->real_escape_string(trim($_POST['codigo']));
        $categoria = intval($_POST['categoria']);
        $proveedor = intval($_POST['provedor']);
        $marca = intval($_POST['marca']);
        $stock = $conexion->real_escape_string(trim($_POST['stock']));
        $id_unit_medida = 0; // Valor predeterminado
        $usId = $_SESSION['usId'];
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            //Actualizar el producto del usuario sin cambiar la imagen en la base de datos
            $idProducto = intval($_POST['idProducto']);
            $sql = "UPDATE producto SET nombre='$nombre', descripcion='$descripcion', precio=$precio, codigo='$codigo', id_categorias=$categoria, stock='$stock', id_unit_medida=$id_unit_medida,
                        id_provedor = $proveedor, id_marca = $marca
                    WHERE idProducto=$idProducto";
            if ($conexion->query($sql) === TRUE) {
                $mensaje = "✅ Producto actualizado correctamente.";
                $tipoAlerta = "success";
                echo "Producto actualizado sin cambiar imagen.";
                //ir a la pagina de lista_productos.php
                @header("Location: ./lista_productos.php");
                exit();
            } else {
                $mensaje = "❌ Error al actualizar el producto: " . $conexion->error;

                $tipoAlerta = "danger";
                //ir a la pagina de lista_productos.php
                @header("Location: ./lista_productos.php");
            }
        } else {

            $imagenData = file_get_contents($_FILES['imagen']['tmp_name']);
            $idProducto = intval($_POST['idProducto']);

            $stmt = $conexion->prepare("
        UPDATE producto 
        SET nombre=?, descripcion=?, precio=?, codigo=?, id_categorias=?, stock=?, imagen=?, id_unit_medida=?, id_provedor=?, id_marca=?
        WHERE idProducto=? AND id_user=?
    ");

            $null = NULL;

            $stmt->bind_param(
                "ssdsiibiii",
                $nombre,
                $descripcion,
                $precio,
                $codigo,
                $categoria,
                $stock,
                $null,
                $id_unit_medida,
                $proveedor,
                $marca,
                $idProducto,
                $usId
            );

            // 👇 AQUÍ SE ENVÍA EL BINARIO REAL
            $stmt->send_long_data(6, $imagenData);

            if ($stmt->execute()) {
                @header("Location: ./lista_productos.php");
                exit();
            } else {
                die("Error imagen: " . $stmt->error);
                @header("Location: ./lista_productos.php");
            }
        }
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
        $tipoAlerta = "warning";
        echo "Faltan campos obligatorios.";
    }
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
