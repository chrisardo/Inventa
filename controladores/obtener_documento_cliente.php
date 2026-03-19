<?php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];
$idCliente = intval($_POST['idCliente']);

$sql = "SELECT dni_o_ruc FROM clientes 
        WHERE idCliente = '$idCliente' 
        AND id_user = '$usId'";

$result = mysqli_query($conexion, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        "documento" => $row['dni_o_ruc']
    ]);
} else {
    echo json_encode([
        "documento" => ""
    ]);
}