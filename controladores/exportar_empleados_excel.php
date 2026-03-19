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
        e.nombre,
        e.apellido,
        e.dni,
        e.celular,
        e.email,
        e.direccion,
        e.provincia,
        e.distrito,
        d.nombre AS departamento,
        e.estado,
        e.fecha_registro
    FROM empleados e
    LEFT JOIN departamento d ON d.id_departamento = e.id_departamento
    WHERE e.id_user = $usId
    ORDER BY e.nombre ASC
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
$sheet->setTitle('Empleados');

// Encabezados
$headers = [
    'A1' => 'Nombre',
    'B1' => 'Apellido',
    'C1' => 'DNI',
    'D1' => 'Celular',
    'E1' => 'Email',
    'F1' => 'Dirección',
    'G1' => 'Provincia',
    'H1' => 'Distrito',
    'I1' => 'Departamento',
    'J1' => 'Estado',
    'K1' => 'Fecha Registro'
];

foreach ($headers as $cell => $text) {
    $sheet->setCellValue($cell, $text);
}

// Estilos encabezado
$sheet->getStyle('A1:K1')->applyFromArray([
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
    $sheet->setCellValue("B{$fila}", $row['apellido']);
    $sheet->setCellValue("C{$fila}", $row['dni']);
    $sheet->setCellValue("D{$fila}", $row['celular']);
    $sheet->setCellValue("E{$fila}", $row['email']);
    $sheet->setCellValue("F{$fila}", $row['direccion']);
    $sheet->setCellValue("G{$fila}", $row['provincia']);
    $sheet->setCellValue("H{$fila}", $row['distrito']);
    $sheet->setCellValue("I{$fila}", $row['departamento']);
    $sheet->setCellValue("J{$fila}", $row['estado']);
    $sheet->setCellValue("K{$fila}", $row['fecha_registro']);

    $fila++;
}

// Autoajustar columnas
foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================
//   DESCARGA
// ============================
$filename = "empleados_" . date('Ymd_His') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;