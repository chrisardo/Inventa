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

// Crear PDF horizontal
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// Título
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('LISTA DE CLIENTES'), 0, 1, 'C');
$pdf->Ln(3);

// Encabezados
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(220, 220, 220);

//$pdf->Cell(8, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Nro. Docum', 1, 0, 'C', true);
$pdf->Cell(22, 8, 'Celular', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Email', 1, 0, 'C', true);
$wpdf = 40;
$pdf->Cell($wpdf, 8, 'Direccion', 1, 0, 'C', true);
$pdf->Cell(22, 8, 'Prov.', 1, 0, 'C', true);
$pdf->Cell(22, 8, 'Dist.', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Rubro', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Depto', 1, 0, 'C', true);
$pdf->Cell(18, 8, 'Img', 1, 1, 'C', true);

// Filas
$pdf->SetFont('Arial', '', 8);

// Consulta SQL
$query = "SELECT c.idCliente, c.nombre, c.dni_o_ruc, c.celular, c.email, 
                 c.direccion, c.provincia, c.distrito, 
                 r.nombre AS rubro,
                 d.nombre AS departamento,
                 c.imagen
          FROM clientes c
          LEFT JOIN rubros r ON r.id_rubro = c.id_rubro
          LEFT JOIN departamento d ON d.id_departamento = c.id_departamento
          WHERE c.id_user = $usId
          ORDER BY c.nombre ASC";


$result = $conexion->query($query);

if (!$result) {
    die("<h3>Error SQL:</h3>" . $conexion->error);
}

while ($fila = $result->fetch_assoc()) {

    //$pdf->Cell(8, 20, $fila['idCliente'], 1, 0, 'C');
    $pdf->Cell(35, 20, utf8_decode($fila['nombre']), 1, 0, 'L');
    $pdf->Cell(20, 20, $fila['dni_o_ruc'], 1, 0, 'C');
    $pdf->Cell(22, 20, $fila['celular'], 1, 0, 'C');
    $pdf->Cell(35, 20, utf8_decode($fila['email']), 1, 0, 'L');
    $pdf->Cell($wpdf, 20, utf8_decode($fila['direccion']), 1, 0, 'L');
    $pdf->Cell(22, 20, utf8_decode($fila['provincia']), 1, 0, 'L');
    $pdf->Cell(22, 20, utf8_decode($fila['distrito']), 1, 0, 'L');

    // Rubro y Departamento ya con nombres
    $pdf->Cell(15, 20, utf8_decode($fila['rubro']), 1, 0, 'L');
    $pdf->Cell(15, 20, utf8_decode($fila['departamento']), 1, 0, 'L');

    // Imagen...
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    if (!empty($fila['imagen'])) {

        $info = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($info, $fila['imagen']);
        finfo_close($info);

        $ext = '.jpg';
        if ($mime === 'image/png') $ext = '.png';
        if ($mime === 'image/gif') $ext = '.gif';

        $tempImg = tempnam(sys_get_temp_dir(), 'cli_') . $ext;

        file_put_contents($tempImg, $fila['imagen']);

        $pdf->Image($tempImg, $x + 1, $y + 2, 16, 16);

        unlink($tempImg);
    }

    $pdf->Cell(18, 20, '', 1, 1, 'C');
}
// Salida del PDF
$pdf->Output();
exit;
