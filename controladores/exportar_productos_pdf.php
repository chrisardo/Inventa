<?php
session_start();
require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/../fpdf/fpdf.php";

/* ======================================================
   CLASE PDF EXTENDIDA
====================================================== */
class PDF extends FPDF {

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;

        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }
}

/* ======================================================
   FUNCIÓN IMAGEN SEGURA
====================================================== */
function mostrarImagenFPDF($pdf, $blob, $x, $y, $w, $h) {
    if (empty($blob)) return;

    $info = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_buffer($info, $blob);
    finfo_close($info);

    switch ($mime) {
        case 'image/jpeg': $type = 'JPG'; $ext = '.jpg'; break;
        case 'image/png':  $type = 'PNG'; $ext = '.png'; break;
        case 'image/gif':  $type = 'GIF'; $ext = '.gif'; break;
        default: return;
    }

    $tmp = tempnam(sys_get_temp_dir(), 'img_') . $ext;
    file_put_contents($tmp, $blob);
    $pdf->Image($tmp, $x, $y, $w, $h, $type);
    unlink($tmp);
}

/* ======================================================
   VALIDAR SESIÓN
====================================================== */
if (!isset($_SESSION['usId'])) die("Acceso no autorizado.");
$usId = (int)$_SESSION['usId'];

/* ======================================================
   DATOS EMPRESA
====================================================== */
$empresa = "EMPRESA";
$logoEmpresa = null;

$resEmp = $conexion->query("
    SELECT nombreEmpresa, imagen 
    FROM usuario_acceso 
    WHERE id_user = $usId
");

if ($resEmp && $row = $resEmp->fetch_assoc()) {
    $empresa = $row['nombreEmpresa'];
    $logoEmpresa = $row['imagen'];
}

$fechaExportacion = date("d/m/Y H:i");

/* ======================================================
   CREAR PDF
====================================================== */
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

/* ======================================================
   LOGO
====================================================== */
if ($logoEmpresa) {
    mostrarImagenFPDF($pdf, $logoEmpresa, 10, 10, 30, 30);
}

/* ======================================================
   TÍTULOS
====================================================== */
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('LISTA DE PRODUCTOS - INVENTA'), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode('Empresa: ' . $empresa), 0, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, utf8_decode('Fecha de exportación: ' . $fechaExportacion), 0, 1, 'C');
$pdf->Ln(4);

/* ======================================================
   ANCHOS DE COLUMNAS
====================================================== */
$w = [22, 45, 18, 18, 14, 32, 32, 26, 26, 18];

/* ======================================================
   ENCABEZADOS
====================================================== */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(220,220,220);

$headers = ['SKU/Código','Producto','Precio','Costo','Stock','Tienda','Proveedor','Categoría','Marca','Imagen'];
foreach ($headers as $i => $h) {
    $pdf->Cell($w[$i], 8, utf8_decode($h), 1, 0, 'C', true);
}
$pdf->Ln();

/* ======================================================
   CUERPO
====================================================== */
$pdf->SetFont('Arial', '', 7.5);

$query = "
    SELECT 
        p.codigo,
        p.nombre,
        p.imagen,
        p.precio,
        p.costo_compra,
        p.stock,
        IFNULL(s.nombre,'SIN TIENDA') AS sucursal,
        IFNULL(c.nombre,'SIN CATEGORÍA') AS categoria,
        IFNULL(prov.nombre,'SIN PROVEEDOR') AS provedor,
        IFNULL(m.nombre,'SIN MARCA') AS marca
    FROM producto p
    LEFT JOIN sucursal s ON s.id_sucursal = p.id_sucursal
    LEFT JOIN categorias c ON c.id_categorias = p.id_categorias
    LEFT JOIN provedores prov ON prov.id_provedor = p.id_provedor
    LEFT JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.id_user = $usId AND p.Eliminado = 0
    ORDER BY p.idProducto DESC
";

$result = $conexion->query($query);

while ($fila = $result->fetch_assoc()) {

    $producto = utf8_decode($fila['nombre']);
    $lineas = $pdf->NbLines($w[1], $producto);
    $altura = $lineas * 4.5;   // 🔥 SIN altura mínima

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->Cell($w[0], $altura, $fila['codigo'], 1, 0, 'C');

    $xProd = $pdf->GetX();
    $yProd = $pdf->GetY();
    $pdf->MultiCell($w[1], 4.5, $producto, 1);

    $pdf->SetXY($xProd + $w[1], $yProd);

    $pdf->Cell($w[2], $altura, number_format($fila['precio'],2), 1, 0, 'R');
    $pdf->Cell($w[3], $altura, number_format($fila['costo_compra'],2), 1, 0, 'R');
    $pdf->Cell($w[4], $altura, $fila['stock'], 1, 0, 'C');
    $pdf->Cell($w[5], $altura, utf8_decode($fila['sucursal']), 1, 0);
    $pdf->Cell($w[6], $altura, utf8_decode($fila['provedor']), 1, 0);
    $pdf->Cell($w[7], $altura, utf8_decode($fila['categoria']), 1, 0);
    $pdf->Cell($w[8], $altura, utf8_decode($fila['marca']), 1, 0);

    // Imagen centrada verticalmente
    $xImg = $pdf->GetX();
    $yImg = $pdf->GetY();
    $imgSize = min(14, $altura - 2);
    $imgY = $yImg + (($altura - $imgSize) / 2);

    mostrarImagenFPDF($pdf, $fila['imagen'], $xImg + 2, $imgY, $imgSize, $imgSize);

    $pdf->Cell($w[9], $altura, '', 1, 1);
}

/* ======================================================
   SALIDA
====================================================== */
$pdf->Output('D', 'lista_productos.pdf');
exit;
