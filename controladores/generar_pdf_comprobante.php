<?php
require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/../fpdf/fpdf.php";
session_start();

$id_ticket = intval($_GET['id']);
$id_user   = $_SESSION['usId'];

/* =========================
   FUNCION MONTO EN LETRAS
========================= */

function numeroALetras($numero) {

    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    $parteEntera = floor($numero);
    $parteDecimal = round(($numero - $parteEntera) * 100);

    $letras = strtoupper($formatter->format($parteEntera));

    return $letras . " CON " . str_pad($parteDecimal, 2, "0", STR_PAD_LEFT) . "/100 SOLES";
}


/* =========================
   EMPRESA
========================= */

$sqlEmpresa = "SELECT nombreEmpresa, imagen, direccion, ruc, celular, email
               FROM usuario_acceso
               WHERE id_user = '$id_user'";

$resEmp = mysqli_query($conexion, $sqlEmpresa);
$empresa = mysqli_fetch_assoc($resEmp);


/* =========================
   VENTA
========================= */

$sql = "SELECT tv.*, 
               c.nombre as cliente,
               c.dni_o_ruc,
               c.direccion as direccion_cliente,
               mp.nombre as metodo_pago
        FROM ticket_ventas tv
        LEFT JOIN clientes c ON tv.idCliente = c.idCliente
        LEFT JOIN metodo_pago mp ON tv.id_metodo_pago = mp.id_metodo_pago
        WHERE tv.id_ticket_ventas = '$id_ticket'";

$res = mysqli_query($conexion, $sql);
$venta = mysqli_fetch_assoc($res);


if(!$venta){
    die("Venta no encontrada");
}


/* =========================
   TIPO COMPROBANTE
========================= */

$tipoComprobante = strtoupper($venta['tipo_comprobante']);

if($tipoComprobante == "BOLETA"){
    $titulo = "BOLETA ELECTRÓNICA";
} else {
    $titulo = "FACTURA ELECTRÓNICA";
}


/* =========================
   CLIENTE VARIOS
========================= */

$nombreCliente = $venta['cliente'];

if(strtolower($venta['idCliente']) == 0){
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

$pdf = new FPDF('P','mm','A4');
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
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,8,"RUC: ".$empresa['ruc'],1,2,'C');
$pdf->Cell(60,8,utf8_decode($titulo),1,2,'C');
$pdf->Cell(60,8,$venta['serie']."-".str_pad($venta['numero'],8,"0",STR_PAD_LEFT),1,1,'C');


/* DATOS EMPRESA */
$pdf->SetXY(10, 45);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,6,utf8_decode($empresa['nombreEmpresa']),0,1);

$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,"Direccion: ".utf8_decode($empresa['direccion']),0,1);
$pdf->Cell(0,6,"Celular: ".$empresa['celular']." | Email: ".$empresa['email'],0,1);

$pdf->Ln(5);


/* DATOS CLIENTE */

$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6,"Cliente:",0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90,6,utf8_decode($nombreCliente),0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,6,"Fecha emision:",0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,6,$venta['fecha_venta'],0,1);



/* DNI / RUC */
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6,"DNI/RUC:",0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90,6,$venta['dni_o_ruc'],0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,6,"Pago:",0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,6,utf8_decode($venta['metodo_pago']),0,1);


/* Dirección cliente debajo */
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6,"Direccion:",0,0);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,6,utf8_decode($venta['direccion_cliente']));

$pdf->Ln(5);


/* TABLA */

$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(230,230,230);

$pdf->Cell(15,8,"Cant",1,0,'C',true);
$pdf->Cell(95,8,"Descripcion",1,0,'C',true);
$pdf->Cell(30,8,"P.Unit",1,0,'C',true);
$pdf->Cell(30,8,"Total",1,1,'C',true);

$pdf->SetFont('Arial','',10);

$sqlDetalle = "SELECT p.nombre, d.cantidad_pedido_producto, d.sub_total
               FROM detalle_ticket_ventas d
               INNER JOIN producto p ON d.idProducto = p.idProducto
               WHERE d.id_ticket_ventas = '$id_ticket'";

$resDetalle = mysqli_query($conexion, $sqlDetalle);

while ($row = mysqli_fetch_assoc($resDetalle)) {

    $cantidad = $row['cantidad_pedido_producto'];
    $totalLinea = $row['sub_total'];
    $precioUnit = $totalLinea / $cantidad;

    $pdf->Cell(15,8,$cantidad,1,0,'C');
    $pdf->Cell(95,8,utf8_decode($row['nombre']),1,0);
    $pdf->Cell(30,8,"S/ ".number_format($precioUnit,2),1,0,'R');
    $pdf->Cell(30,8,"S/ ".number_format($totalLinea,2),1,1,'R');
}


/* TOTALES */

$pdf->Ln(5);
$pdf->SetX(110);

$pdf->Cell(50,8,"Subtotal:",0,0,'R');
$pdf->Cell(30,8,"S/ ".number_format($subtotal,2),0,1,'R');

$pdf->SetX(110);

if ($venta['aplica_igv'] == 1) {
    $pdf->Cell(50,8,"IGV (18%):",0,0,'R');
    $pdf->Cell(30,8,"S/ ".number_format($igv,2),0,1,'R');
} else {
    $pdf->Cell(50,8,"IGV:",0,0,'R');
    $pdf->Cell(30,8,"EXONERADO",0,1,'R');
}

$pdf->SetFont('Arial','B',12);
$pdf->SetX(110);
$pdf->Cell(50,10,"TOTAL A PAGAR:",0,0,'R');
$pdf->Cell(30,10,"S/ ".number_format($total,2),0,1,'R');


/* MONTO EN LETRAS */

$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,6,"SON: ".numeroALetras($total));


$pdf->Ln(10);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(0,6,"Gracias por su preferencia.",0,1,'C');

$pdf->Output();
?>