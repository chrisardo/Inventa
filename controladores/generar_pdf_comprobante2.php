<?php
require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/../fpdf/fpdf.php";
session_start();

$id_ticket = intval($_GET['id']);
$id_user   = $_SESSION['usId'];
$tipoElegido = isset($_GET['tipo']) ? strtoupper($_GET['tipo']) : null;

if ($tipoElegido !== "FACTURA" && $tipoElegido !== "BOLETA") {
    die("Tipo inválido");
}

/* =========================
   FUNCION MONTO EN LETRAS
========================= */
function numeroALetras($numero)
{
    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    $parteEntera = floor($numero);
    $parteDecimal = round(($numero - $parteEntera) * 100);
    $letras = strtoupper($formatter->format($parteEntera));
    return $letras . " CON " . str_pad($parteDecimal, 2, "0", STR_PAD_LEFT) . "/100 SOLES";
}

/* =========================
   EMPRESA
========================= */
$stmtEmp = $conexion->prepare("
    SELECT nombreEmpresa, imagen, direccion, ruc, celular, email
    FROM usuario_acceso
    WHERE id_user = ?
");
$stmtEmp->bind_param("i", $id_user);
$stmtEmp->execute();
$empresa = $stmtEmp->get_result()->fetch_assoc();
$stmtEmp->close();

/* =========================
   VENTA
========================= */
$stmtVenta = $conexion->prepare("
    SELECT tv.*, 
           c.nombre as cliente,
           c.dni_o_ruc,
           c.direccion as direccion_cliente,
           mp.nombre as metodo_pago
    FROM ticket_ventas tv
    LEFT JOIN clientes c ON tv.idCliente = c.idCliente
    LEFT JOIN metodo_pago mp ON tv.id_metodo_pago = mp.id_metodo_pago
    WHERE tv.id_ticket_ventas = ?
      AND tv.id_user = ?
");
$stmtVenta->bind_param("ii", $id_ticket, $id_user);
$stmtVenta->execute();
$venta = $stmtVenta->get_result()->fetch_assoc();
$stmtVenta->close();

if (!$venta) {
    die("Venta no encontrada");
}

/* =========================
   GENERAR SERIE Y NUMERO SI ES NECESARIO
========================= */

$serieNueva = ($tipoElegido === "FACTURA") ? "F001" : "B001";

$necesitaGenerar = (
    $venta['tipo_comprobante'] == "Sin comprobante" ||
    empty($venta['serie']) ||
    empty($venta['numero'])
);

if ($necesitaGenerar) {

    // Obtener siguiente número correlativo
    $stmtMax = $conexion->prepare("
        SELECT IFNULL(MAX(numero),0) + 1 as siguiente
        FROM ticket_ventas
        WHERE id_user = ?
          AND serie = ?
    ");
    $stmtMax->bind_param("is", $id_user, $serieNueva);
    $stmtMax->execute();
    $numeroNuevo = $stmtMax->get_result()->fetch_assoc()['siguiente'];
    $stmtMax->close();

    // Actualizar venta
    $stmtUpdate = $conexion->prepare("
        UPDATE ticket_ventas
        SET tipo_comprobante = ?,
            serie = ?,
            numero = ?
        WHERE id_ticket_ventas = ?
          AND id_user = ?
    ");
    $stmtUpdate->bind_param(
        "ssiii",
        $tipoElegido,
        $serieNueva,
        $numeroNuevo,
        $id_ticket,
        $id_user
    );
    $stmtUpdate->execute();
    $stmtUpdate->close();

    $venta['serie'] = $serieNueva;
    $venta['numero'] = $numeroNuevo;
    $venta['tipo_comprobante'] = $tipoElegido;
}

/* =========================
   TITULO
========================= */

$titulo = ($venta['tipo_comprobante'] == "FACTURA")
    ? "FACTURA ELECTRÓNICA"
    : "BOLETA ELECTRÓNICA";
/* =========================
   CLIENTE VARIOS
========================= */

$nombreCliente = $venta['cliente'];

if (strtolower($venta['idCliente']) == 0) {
    $nombreCliente = "Clientes varios";
}

/* =========================
   CALCULOS IGV
========================= */
$total = floatval($venta['total_venta']);

if ($venta['aplica_igv'] == 1) {
    $igv = $total - ($total / 1.18);
    $subtotal = $total - $igv;
} else {
    $igv = 0;
    $subtotal = $total;
}

/* =========================
   PDF
========================= */

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

/* LOGO */
if (!empty($empresa['imagen'])) {
    $rutaTemp = "logo_temp.png";
    file_put_contents($rutaTemp, $empresa['imagen']);
    $pdf->Image($rutaTemp, 10, 10, 35);
    unlink($rutaTemp);
}

/* CAJA DERECHA */
$pdf->SetXY(140, 10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 8, "RUC: " . $empresa['ruc'], 1, 2, 'C');
$pdf->Cell(60, 8, utf8_decode($titulo), 1, 2, 'C');
$pdf->Cell(
    60,
    8,
    $venta['serie'] . "-" . str_pad($venta['numero'], 8, "0", STR_PAD_LEFT),
    1,
    1,
    'C'
);

/* EMPRESA */
$pdf->SetXY(10, 45);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 6, utf8_decode($empresa['nombreEmpresa']), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, "Direccion: " . utf8_decode($empresa['direccion']), 0, 1);
$pdf->Cell(0, 6, "Celular: " . $empresa['celular'] . " | Email: " . $empresa['email'], 0, 1);

$pdf->Ln(5);

/* CLIENTE */
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, "Cliente:", 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 6, utf8_decode($nombreCliente), 0, 0);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, "Fecha Emision:", 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, $venta['fecha_venta'], 0, 1);

/* DNI */
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, "DNI/RUC:", 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 6, $venta['dni_o_ruc'], 0, 1);

/* DIRECCION DEBAJO DEL DNI */
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, "Direccion:", 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 6, utf8_decode($venta['direccion_cliente']), 0, 1);

$pdf->Ln(5);

/* TABLA */
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(15, 8, "Cant", 1, 0, 'C', true);
$pdf->Cell(95, 8, "Descripcion", 1, 0, 'C', true);
$pdf->Cell(30, 8, "P.Unit", 1, 0, 'C', true);
$pdf->Cell(30, 8, "Total", 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);

$stmtDetalle = $conexion->prepare("
    SELECT p.nombre, d.cantidad_pedido_producto, d.sub_total
    FROM detalle_ticket_ventas d
    INNER JOIN producto p ON d.idProducto = p.idProducto
    WHERE d.id_ticket_ventas = ?
");
$stmtDetalle->bind_param("i", $id_ticket);
$stmtDetalle->execute();
$resDetalle = $stmtDetalle->get_result();

while ($row = $resDetalle->fetch_assoc()) {

    $cantidad = $row['cantidad_pedido_producto'];
    $totalLinea = $row['sub_total'];
    $precioUnit = $totalLinea / $cantidad;

    $pdf->Cell(15, 8, $cantidad, 1, 0, 'C');
    $pdf->Cell(95, 8, utf8_decode($row['nombre']), 1, 0);
    $pdf->Cell(30, 8, "S/ " . number_format($precioUnit, 2), 1, 0, 'R');
    $pdf->Cell(30, 8, "S/ " . number_format($totalLinea, 2), 1, 1, 'R');
}

$stmtDetalle->close();

/* TOTALES */
$pdf->Ln(5);
$pdf->SetX(110);
$pdf->Cell(50, 8, "Subtotal:", 0, 0, 'R');
$pdf->Cell(30, 8, "S/ " . number_format($subtotal, 2), 0, 1, 'R');

$pdf->SetX(110);
if ($venta['aplica_igv'] == 1) {
    $pdf->Cell(50, 8, "IGV (18%):", 0, 0, 'R');
    $pdf->Cell(30, 8, "S/ " . number_format($igv, 2), 0, 1, 'R');
} else {
    $pdf->Cell(50, 8, "IGV:", 0, 0, 'R');
    $pdf->Cell(30, 8, "EXONERADO", 0, 1, 'R');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX(110);
$pdf->Cell(50, 10, "TOTAL:", 0, 0, 'R');
$pdf->Cell(30, 10, "S/ " . number_format($total, 2), 0, 1, 'R');

/* MONTO EN LETRAS */
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(0, 6, "SON: " . numeroALetras($total));

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 6, "Gracias por su preferencia.", 0, 1, 'C');

$pdf->Output();
