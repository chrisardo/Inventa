<?php
session_start();
require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/../fpdf/fpdf.php";

// ============================
//   VALIDAR USUARIO LOGUEADO
// ============================
if (!isset($_SESSION['usId'])) {
    die("Acceso no autorizado.");
}

$usId = intval($_SESSION['usId']);

// ============================
//      CREAR PDF HORIZONTAL
// ============================
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// ============================
//      TÍTULO
// ============================
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('LISTA DE PRODUCTOS - INVENTA'), 0, 1, 'C');
$pdf->Ln(3);
$w = [
    23, // SKU
    50, // Producto
    16, // Precio
    16, // Stock
    36, // Empresa
    36, // Proveedor
    28, // Categoria
    28, // Marca
    24, // Fecha
    20  // Imagen
];

// ============================
//      ENCABEZADOS
// ============================
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(220, 220, 220);


$pdf->Cell($w[0], 8, 'SKU/Codigo', 1, 0, 'C', true);
$pdf->Cell($w[1], 8, 'Producto', 1, 0, 'C', true);
$pdf->Cell($w[2], 8, 'Precio', 1, 0, 'C', true);
$pdf->Cell($w[3], 8, 'Stock', 1, 0, 'C', true);
$pdf->Cell($w[4], 8, 'Empresa', 1, 0, 'C', true);
$pdf->Cell($w[5], 8, 'Proveedor', 1, 0, 'C', true);
$pdf->Cell($w[6], 8, utf8_decode('Categoría'), 1, 0, 'C', true);
$pdf->Cell($w[7], 8, 'Marca', 1, 0, 'C', true);
$pdf->Cell($w[8], 8, 'Fecha Reg.', 1, 0, 'C', true);
$pdf->Cell($w[9], 8, 'Imagen', 1, 1, 'C', true);


// ============================
//      CUERPO
// ============================
$pdf->SetFont('Arial', '', 8);

// ============================
//      CONSULTA SQL (SOLO USUARIO LOGUEADO)
// ============================
$query = "
    SELECT 
        p.codigo,
        p.nombre,
        p.imagen,
        p.precio,
        p.stock,
        u.nombreEmpresa,
        IFNULL(c.nombre, 'SIN CATEGORÍA') AS categoria,
        IFNULL(prov.nombre, 'SIN PROVEEDOR') AS provedor,
        IFNULL(m.nombre, 'SIN MARCA') AS marca,
        p.fecha_registro
    FROM producto p
    LEFT JOIN usuario_acceso u ON u.id_user = p.id_user
    LEFT JOIN categorias c ON c.id_categorias = p.id_categorias
    LEFT JOIN provedores prov ON prov.id_provedor = p.id_provedor
    LEFT JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.id_user = $usId and p.Eliminado = 0
    ORDER BY p.idProducto DESC
";

$result = $conexion->query($query);

if (!$result) {
    die("<h3>Error SQL:</h3> " . $conexion->error);
}

// ============================
//      RECORRER PRODUCTOS
// ============================
while ($fila = $result->fetch_assoc()) {

    // ✅ Formatear SOLO FECHA (SIN HORA)
    $fecha = $fila['fecha_registro'];
    if ($fecha && $fecha != "0000-00-00") {
        $fecha = date("d/m/Y", strtotime($fecha));
    } else {
        $fecha = "";
    }

    $pdf->Cell($w[0], 20, utf8_decode($fila['codigo']), 1, 0, 'C');
    $pdf->Cell($w[1], 20, utf8_decode($fila['nombre']), 1, 0, 'L');
    $pdf->Cell($w[2], 20, number_format($fila['precio'], 2), 1, 0, 'C');
    $pdf->Cell($w[3], 20, $fila['stock'], 1, 0, 'C');
    $pdf->Cell($w[4], 20, utf8_decode($fila['nombreEmpresa']), 1, 0, 'L');
    $pdf->Cell($w[5], 20, utf8_decode($fila['provedor']), 1, 0, 'L');
    $pdf->Cell($w[6], 20, utf8_decode($fila['categoria']), 1, 0, 'L');
    $pdf->Cell($w[7], 20, utf8_decode($fila['marca']), 1, 0, 'L');
    $pdf->Cell($w[8], 20, $fecha, 1, 0, 'C');

    // ============================
    //      IMAGEN DESDE LONGBLOB
    // ============================
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    if (!empty($fila['imagen'])) {

        $info = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($info, $fila['imagen']);
        finfo_close($info);

        $ext = '.jpg';
        if ($mime === 'image/png') $ext = '.png';
        if ($mime === 'image/gif') $ext = '.gif';

        $tempImg = tempnam(sys_get_temp_dir(), 'prod_') . $ext;
        file_put_contents($tempImg, $fila['imagen']);

        $pdf->Image($tempImg, $x + 2, $y + 2, 16, 16);

        unlink($tempImg);
    }

    $pdf->Cell(20, 20, '', 1, 1, 'C');
}

// ============================
//      SALIDA PDF
// ============================
$pdf->Output('I', 'lista_productos.pdf');
exit;
