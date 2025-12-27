<?php
//Toda esta parte es controladores/guardar_suscripcion.php
session_start();
include 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$usId = $_SESSION['usId'];
$subscriptionID = $data['subscriptionID'];
$tipoPlan = $data['tipo_plan'];
$paypalPlanID = $data['paypal_plan_id'];

$fechaInicio = date('Y-m-d');

if ($tipoPlan === 'MENSUAL') {
    $fechaFin = date('Y-m-d', strtotime('+1 month'));
    $monto = 11;
} else {
    $fechaFin = date('Y-m-d', strtotime('+1 year'));
    $monto = 110;
}

// Desactivar suscripciones anteriores
$conexion->query("UPDATE suscripcion SET estado_suscripcion='INACTIVA' WHERE id_user=$usId");

$stmt = $conexion->prepare("
INSERT INTO suscripcion
(id_user, pago, metodo_pago, fecha_suscripcion, fecha_vencimiento, estado_suscripcion, paypal_subscription_id, paypal_plan_id, tipo_plan)
VALUES (?, ?, 'PAYPAL', ?, ?, 'ACTIVE', ?, ?, ?)
");

$stmt->bind_param(
    "idsssss",
    $usId,
    $monto,
    $fechaInicio,
    $fechaFin,
    $subscriptionID,
    $paypalPlanID,
    $tipoPlan
);

$stmt->execute();
