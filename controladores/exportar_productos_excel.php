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

// =============================
//      CREAR ARCHIVO EXCEL
// =============================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Productos");

// =============================
//            TÍTULO
// =============================
$sheet->mergeCells("A1:K1");
$sheet->setCellValue("A1", "LISTA DE PRODUCTOS - INVENTA");
$sheet->getStyle("A1")->getFont()->setBold(true)->setSize(18);
$sheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getRowDimension(1)->setRowHeight(28);

// =============================
//         ENCABEZADOS
// =============================
$encabezados = [
    "Empresa",
    "Producto",
    "SKU",
    "Stock",
    "Tienda",
    "Categoria",
    "Marca",
    "Proveedor",
    "Costo compra",
    "Precio venta",
    "Fecha Registro"
];

$rowHeader = 3;
$col = "A";

foreach ($encabezados as $enc) {
    $sheet->setCellValue($col . $rowHeader, $enc);
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $col++;
}

// =============================
//     ESTILO DE ENCABEZADOS
// =============================
$sheet->getStyle("A{$rowHeader}:K{$rowHeader}")->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['rgb' => '28A745']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);

$sheet->getRowDimension($rowHeader)->setRowHeight(20);

// =============================
//      CONSULTA SQL
// =============================
$query = "
    SELECT 
        p.codigo,
        p.nombre,
        p.precio,
        p.costo_compra,
        p.stock,
        u.nombreEmpresa,
        IFNULL(s.nombre, 'SIN TIENDA') AS sucursal,
        IFNULL(c.nombre, 'SIN CATEGORÍA') AS categoria,
        IFNULL(prov.nombre, 'SIN PROVEEDOR') AS proveedor,
        IFNULL(m.nombre, 'SIN MARCA') AS marca,
        p.fecha_registro
    FROM producto p
    LEFT JOIN usuario_acceso u ON u.id_user = p.id_user
    LEFT JOIN sucursal s ON s.id_sucursal = p.id_sucursal
    LEFT JOIN categorias c ON c.id_categorias = p.id_categorias
    LEFT JOIN provedores prov ON prov.id_provedor = p.id_provedor
    LEFT JOIN marcas m ON m.id_marca = p.id_marca
    WHERE p.id_user = $usId AND p.Eliminado = 0
    ORDER BY p.idProducto DESC
";

$result = $conexion->query($query);

if (!$result) {
    die('Error SQL: ' . $conexion->error);
}

// =============================
//        LLENAR DATOS
// =============================
$filaExcel = 4;

while ($fila = $result->fetch_assoc()) {

    // Formato de fecha
    $fecha = "";
    if (!empty($fila['fecha_registro']) && $fila['fecha_registro'] !== "0000-00-00") {
        $fecha = date("d/m/Y", strtotime($fila['fecha_registro']));
    }

    $sheet->setCellValue("A{$filaExcel}", $fila['nombreEmpresa']);
    $sheet->setCellValue("B{$filaExcel}", $fila['nombre']);
    $sheet->setCellValue("C{$filaExcel}", $fila['codigo']);
    $sheet->setCellValue("D{$filaExcel}", $fila['stock']);
    $sheet->setCellValue("E{$filaExcel}", $fila['sucursal']);   // ✅ TIENDA
    $sheet->setCellValue("F{$filaExcel}", $fila['categoria']);
    $sheet->setCellValue("G{$filaExcel}", $fila['marca']);
    $sheet->setCellValue("H{$filaExcel}", $fila['proveedor']);
     $sheet->setCellValue("I{$filaExcel}", $fila['costo_compra']);
    $sheet->setCellValue("J{$filaExcel}", $fila['precio']);
    $sheet->setCellValue("K{$filaExcel}", $fecha);

    // Zebra (filas alternas)
    if ($filaExcel % 2 == 0) {
        $sheet->getStyle("A{$filaExcel}:K{$filaExcel}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F2F2F2']
            ]
        ]);
    }

    $filaExcel++;
}

// =============================
//            BORDES
// =============================
$sheet->getStyle("A3:K" . ($filaExcel - 1))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

// =============================
//      DESCARGAR ARCHIVO
// =============================
$archivo = "lista_productos_" . date("Ymd_His") . ".xlsx";

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$archivo\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
