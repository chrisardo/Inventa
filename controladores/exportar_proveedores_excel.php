<?php
session_start();

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ============================
//   VALIDAR USUARIO LOGUEADO
// ============================
if (!isset($_SESSION['usId'])) {
    die("Acceso no autorizado.");
}

$usId = intval($_SESSION['usId']);

// ============================
//   CONSULTA
// ============================
$sql = "
    SELECT 
        c.nombre,
        c.ruc,
        c.celular,
        c.email,
        c.direccion,
        c.provincia,
        c.distrito,
        d.nombre AS departamento
    FROM provedores c
    LEFT JOIN departamento d ON d.id_departamento = c.id_departamento
    WHERE c.id_user = $usId
      AND c.Eliminado = 0
    ORDER BY c.nombre ASC
";

$result = $conexion->query($sql);
if (!$result) {
    die("Error SQL: " . $conexion->error);
}

// ============================
//   CREAR EXCEL
// ============================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Proveedores');

// Encabezados
$headers = [
    'A1' => 'Nombre',
    'B1' => 'RUC',
    'C1' => 'Celular',
    'D1' => 'Email',
    'E1' => 'Dirección',
    'F1' => 'Provincia',
    'G1' => 'Distrito',
    'H1' => 'Departamento'
];

foreach ($headers as $cell => $text) {
    $sheet->setCellValue($cell, $text);
}

// Estilos encabezado
$sheet->getStyle('A1:H1')->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => '000000']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'C6EFCE']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// ============================
//   DATOS
// ============================
$fila = 2;
while ($row = $result->fetch_assoc()) {

    $sheet->setCellValue("A{$fila}", $row['nombre']);
    $sheet->setCellValue("B{$fila}", $row['ruc']);
    $sheet->setCellValue("C{$fila}", $row['celular']);
    $sheet->setCellValue("D{$fila}", $row['email']);
    $sheet->setCellValue("E{$fila}", $row['direccion']);
    $sheet->setCellValue("F{$fila}", $row['provincia']);
    $sheet->setCellValue("G{$fila}", $row['distrito']);
    $sheet->setCellValue("H{$fila}", $row['departamento']);

    $fila++;
}

// Autoajustar columnas
foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================
//   DESCARGA
// ============================
$filename = "proveedores_" . date('Ymd_His') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
