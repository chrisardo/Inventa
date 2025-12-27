<?php
session_start();
require_once __DIR__ . "/conexion.php";
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
// ============================
//   VALIDAR USUARIO LOGUEADO
// ============================
if (!isset($_SESSION['usId'])) {
    die("Acceso no autorizado.");
}
$usId = intval($_SESSION['usId']);

// Crear archivo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Clientes");

// =============================
//      TÍTULO GENERAL
// =============================
$sheet->mergeCells("A1:J1");
$sheet->setCellValue("A1", "LISTA DE CLIENTES - INVENTA");
$sheet->getStyle("A1")->getFont()->setBold(true)->setSize(18);
$sheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getRowDimension(1)->setRowHeight(28);

// =============================
//      ENCABEZADOS (AGREGADO: FECHA REGISTRO)
// =============================
$encabezados = [
    "Nombre",
    "DNI/RUC",
    "Celular",
    "Email",
    "Dirección",
    "Provincia",
    "Distrito",
    "Rubro",
    "Departamento",
    "Fecha Registro"
];

$rowHeader = 3;
$col = "A";

foreach ($encabezados as $enc) {
    $sheet->setCellValue($col . $rowHeader, $enc);
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $col++;
}

// Estilo encabezado
$sheet->getStyle("A{$rowHeader}:J{$rowHeader}")->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['rgb' => '4A90E2']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);

$sheet->getRowDimension($rowHeader)->setRowHeight(20);

// =============================
//      CONSULTA SQL (AGREGADA FECHA REGISTRO)
// =============================
$query = "SELECT c.nombre, c.dni_o_ruc, c.celular, c.email, 
                 c.direccion, c.provincia, c.distrito, 
                 r.nombre AS rubro,
                 d.nombre AS departamento,
                 c.fecha_registro
          FROM clientes c
          LEFT JOIN rubros r ON r.id_rubro = c.id_rubro
          LEFT JOIN departamento d ON d.id_departamento = c.id_departamento
            WHERE c.id_user = '$usId'";

$result = $conexion->query($query);

if (!$result) {
    die("Error SQL: " . $conexion->error);
}

// =============================
//      LLENAR DATOS (AGREGADA ULTIMA COLUMNA)
// =============================
$filaExcel = 4;

while ($fila = $result->fetch_assoc()) {

    // Convertir fecha a formato legible si existe
    $fecha = $fila['fecha_registro'];
    if ($fecha && $fecha != "0000-00-00") {
        $fecha = date("d/m/Y", strtotime($fecha));
    } else {
        $fecha = "";
    }

    $sheet->setCellValue("A{$filaExcel}", $fila['nombre']);
    $sheet->setCellValue("B{$filaExcel}", $fila['dni_o_ruc']);
    $sheet->setCellValue("C{$filaExcel}", $fila['celular']);
    $sheet->setCellValue("D{$filaExcel}", $fila['email']);
    $sheet->setCellValue("E{$filaExcel}", $fila['direccion']);
    $sheet->setCellValue("F{$filaExcel}", $fila['provincia']);
    $sheet->setCellValue("G{$filaExcel}", $fila['distrito']);
    $sheet->setCellValue("H{$filaExcel}", $fila['rubro']);
    $sheet->setCellValue("I{$filaExcel}", $fila['departamento']);
    $sheet->setCellValue("J{$filaExcel}", $fecha);

    // Zebra
    if ($filaExcel % 2 == 0) {
        $sheet->getStyle("A{$filaExcel}:J{$filaExcel}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F2F2F2']
            ]
        ]);
    }

    $filaExcel++;
}

// =============================
//      BORDES
// =============================
$sheet->getStyle("A3:J" . ($filaExcel - 1))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

// =============================
//      DESCARGA
// =============================
$archivo = "lista_clientes.xlsx";
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$archivo\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
