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
//  OBTENER NOMBRE DE EMPRESA
// ============================
$empresa = '';
$sqlEmpresa = "SELECT nombreEmpresa FROM usuario_acceso WHERE id_user = $usId LIMIT 1";
$resEmpresa = $conexion->query($sqlEmpresa);
if ($resEmpresa && $resEmpresa->num_rows > 0) {
    $empresa = $resEmpresa->fetch_assoc()['nombreEmpresa'];
}

// ============================
//  EXTENSIÓN FPDF
// ============================
class PDF extends FPDF
{
    public $empresa;
    public $fechaReporte;

    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, utf8_decode('REPORTE GENERAL DE VENTAS'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, utf8_decode($this->empresa), 0, 1, 'C');

        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 6, utf8_decode('Fecha de exportación: ') . $this->fechaReporte, 0, 1, 'C');

        $this->Ln(3);

        $headers = [
            'Fecha',
            'Serie',
            'Forma Pago',
            'Cliente',
            'Nro Doc',
            'Tienda',
            'Dep',
            'Dist',
            'Prov',
            'Dirección',
            'Rubro',
            'SKU',
            'Producto',
            'Categoría',
            'Marca',
            'Stock',
            'Precio',
            'Cant',
            'SubTotal',
            'Total'
        ];

        $widths = [
            14, 12, 12, 20, 18, 16, 14, 14, 14, 24,
            16, 14, 26, 16, 16, 10, 12, 10, 14, 14
        ];

        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(220, 220, 220);

        foreach ($headers as $i => $col) {
            $this->Cell($widths[$i], 7, utf8_decode($col), 1, 0, 'C', true);
        }
        $this->Ln();
    }

    function Row($data, $widths, $height = 5)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
        }
        $h = $height * $nb;

        if ($this->GetY() + $h > $this->GetPageHeight() - 10) {
            $this->AddPage();
        }

        for ($i = 0; $i < count($data); $i++) {
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $widths[$i], $h);
            $this->MultiCell($widths[$i], $height, utf8_decode($data[$i]), 0, 'L');
            $this->SetXY($x + $widths[$i], $y);
        }
        $this->Ln($h);
    }

    function NbLines($w, $txt)
    {
        $cw = $this->CurrentFont['cw'];
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
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
            if ($c == ' ') $sep = $i;
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

// ============================
//      CREAR PDF
// ============================
$pdf = new PDF('L', 'mm', 'A4');
$pdf->empresa = $empresa;
$pdf->fechaReporte = date('d/m/Y H:i:s');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 6.5);

// ============================
//      CONSULTA SQL (CORREGIDA)
// ============================
$sql = "
SELECT 
    tv.fecha_venta,
    tv.serie_venta,
    tv.id_metodo_pago,
    cl.nombre AS cliente,
    cl.dni_o_ruc,
    IFNULL(s.nombre, 'SIN TIENDA') AS sucursal,
    dep.nombre AS departamento,
    cl.distrito,
    cl.provincia,
    cl.direccion,
    r.nombre AS rubro,
    p.codigo,
    p.nombre AS producto,
    ca.nombre AS categoria,
    IFNULL(m.nombre, 'SIN MARCA') AS marca,
    IFNULL(mp.nombre, 'N/A') AS metodo_pago,
    p.stock,
    p.precio,
    dt.cantidad_pedido_producto,
    dt.sub_total,
    tv.total_venta
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt ON dt.id_ticket_ventas = tv.id_ticket_ventas
LEFT JOIN producto p ON dt.idProducto = p.idProducto
LEFT JOIN sucursal s ON s.id_sucursal = p.id_sucursal
LEFT JOIN metodo_pago mp ON mp.id_metodo_pago = tv.id_metodo_pago
LEFT JOIN clientes cl ON tv.idCliente = cl.idCliente
LEFT JOIN categorias ca ON p.id_categorias = ca.id_categorias
LEFT JOIN rubros r ON cl.id_rubro = r.id_rubro
LEFT JOIN departamento dep ON cl.id_departamento = dep.id_departamento
LEFT JOIN marcas m ON m.id_marca = p.id_marca
WHERE tv.id_user = $usId
ORDER BY tv.id_ticket_ventas DESC
";

$resultado = $conexion->query($sql);
if (!$resultado) {
    die("ERROR SQL: " . $conexion->error);
}

// ============================
//      CUERPO DEL PDF
// ============================
$widths = [
    14, 12, 12, 20, 18, 16, 14, 14, 14, 24,
    16, 14, 26, 16, 16, 10, 12, 10, 14, 14
];

while ($fila = $resultado->fetch_assoc()) {

    $data = [
        $fila['fecha_venta'],
        $fila['serie_venta'],
        $fila['metodo_pago'],
        $fila['cliente'],
        $fila['dni_o_ruc'],
        $fila['sucursal'],
        $fila['departamento'],
        $fila['distrito'],
        $fila['provincia'],
        $fila['direccion'],
        $fila['rubro'],
        $fila['codigo'],
        $fila['producto'],
        $fila['categoria'],
        $fila['marca'],
        $fila['stock'],
        number_format($fila['precio'], 2),
        $fila['cantidad_pedido_producto'],
        number_format($fila['sub_total'], 2),
        number_format($fila['total_venta'], 2)
    ];

    $pdf->Row($data, $widths);
}

// ============================
//      DESCARGAR PDF
// ============================
$pdf->Output('D', 'reporte_ventas_inventa.pdf');
exit;
