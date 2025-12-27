<?php
session_start();
include 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$plan = $data['plan'];
$payerID = $data['payerID'];
$orderID = $data['orderID'];
$usId = $_SESSION['usId'];

// Registrar suscripción en la base de datos
$stmt = $conexion->prepare("INSERT INTO suscripciones (id_user, plan, payerID, orderID, fecha) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("isss", $usId, $plan, $payerID, $orderID);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
