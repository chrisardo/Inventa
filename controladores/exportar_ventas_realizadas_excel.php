<?php
session_start();
require_once __DIR__ . "/conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';

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
$sheet->setTitle("Ventas");

// =============================
//      TÍTULO
// =============================
$sheet->mergeCells("A1:T1");
$sheet->setCellValue("A1", "REPORTE GENERAL DE VENTAS - INVENTA");
$sheet->getStyle("A1")->getFont()->setBold(true)->setSize(16);
$sheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getRowDimension(1)->setRowHeight(30);

// =============================
//      ENCABEZADOS
// =============================
$encabezados = [
    "Empresa",
    "Fecha Venta",
    "Serie",
    "Forma de Pago",
    "Cliente",
    "DNI/RUC",
    "Departamento",
    "Distrito",
    "Provincia",
    "Dirección",
    "Rubro",
    "Código",
    "Producto",
    "Categoría",
    "Marca",
    "Stock",
    "Precio",
    "Cantidad",
    "SubTotal",
    "Total Venta"
];

$filaHeader = 3;
$col = "A";

foreach ($encabezados as $enc) {
    $sheet->setCellValue($col . $filaHeader, $enc);
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $col++;
}

// =============================
//      ESTILO ENCABEZADOS
// =============================
$sheet->getStyle("A{$filaHeader}:T{$filaHeader}")->applyFromArray([
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

$sheet->getRowDimension($filaHeader)->setRowHeight(20);

// =============================
//      CONSULTA SQL (CON MARCA)
// =============================
$sql = "
SELECT 
    u.nombreEmpresa,
    tv.fecha_venta,
    tv.serie_venta,
    tv.forma_pago,
    cl.nombre AS cliente,
    cl.dni_o_ruc,
    dep.nombre AS departamento,
    cl.distrito,
    cl.provincia,
    cl.direccion,
    r.nombre AS rubro,
    p.codigo,
    p.nombre AS producto,
    ca.nombre AS categoria,
    IFNULL(m.nombre, 'SIN MARCA') AS marca,
    p.stock,
    p.precio,
    dt.cantidad_pedido_producto,
    dt.sub_total,
    tv.total_venta
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt ON dt.id_ticket_ventas = tv.id_ticket_ventas
LEFT JOIN clientes cl ON tv.idCliente = cl.idCliente
LEFT JOIN producto p ON dt.idProducto = p.idProducto
LEFT JOIN categorias ca ON p.id_categorias = ca.id_categorias
LEFT JOIN rubros r ON cl.id_rubro = r.id_rubro
LEFT JOIN departamento dep ON cl.id_departamento = dep.id_departamento
LEFT JOIN usuario_acceso u ON tv.id_user = u.id_user
LEFT JOIN marcas m ON m.id_marca = p.id_marca
WHERE tv.id_user = $usId
ORDER BY tv.id_ticket_ventas DESC
";

$resultado = $conexion->query($sql);
if (!$resultado) {
    die("ERROR SQL: " . $conexion->error);
}

// =============================
//      LLENAR DATOS
// =============================
$filaExcel = 4;

while ($fila = $resultado->fetch_assoc()) {

    $sheet->setCellValue("A{$filaExcel}", $fila['nombreEmpresa']);
    $sheet->setCellValue("B{$filaExcel}", $fila['fecha_venta']);
    $sheet->setCellValue("C{$filaExcel}", $fila['serie_venta']);
    $sheet->setCellValue("D{$filaExcel}", $fila['forma_pago']);
    $sheet->setCellValue("E{$filaExcel}", $fila['cliente']);
    $sheet->setCellValue("F{$filaExcel}", $fila['dni_o_ruc']);
    $sheet->setCellValue("G{$filaExcel}", $fila['departamento']);
    $sheet->setCellValue("H{$filaExcel}", $fila['distrito']);
    $sheet->setCellValue("I{$filaExcel}", $fila['provincia']);
    $sheet->setCellValue("J{$filaExcel}", $fila['direccion']);
    $sheet->setCellValue("K{$filaExcel}", $fila['rubro']);
    $sheet->setCellValue("L{$filaExcel}", $fila['codigo']);
    $sheet->setCellValue("M{$filaExcel}", $fila['producto']);
    $sheet->setCellValue("N{$filaExcel}", $fila['categoria']);
    $sheet->setCellValue("O{$filaExcel}", $fila['marca']); // ✅ MARCA
    $sheet->setCellValue("P{$filaExcel}", $fila['stock']);
    $sheet->setCellValue("Q{$filaExcel}", $fila['precio']);
    $sheet->setCellValue("R{$filaExcel}", $fila['cantidad_pedido_producto']);
    $sheet->setCellValue("S{$filaExcel}", $fila['sub_total']);
    $sheet->setCellValue("T{$filaExcel}", $fila['total_venta']);

    // Zebra
    if ($filaExcel % 2 == 0) {
        $sheet->getStyle("A{$filaExcel}:T{$filaExcel}")->applyFromArray([
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
$sheet->getStyle("A3:T" . ($filaExcel - 1))->applyFromArray([
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
$archivo = "reporte_ventas_" . date("Ymd_His") . ".xlsx";

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$archivo\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
