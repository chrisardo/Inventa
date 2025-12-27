<?php
header('Content-Type: application/json');

$clientId = "AQOeswWjLwmuqPv7EsgbJp6-Pq7tSQGjlr5A9PQTV6rKNmBTVZiH2YXKeQa8Ii5BGCkVmYOguvriaCC8";
$secret = "ENJSWgnMnDXbBg6ZyKh_RBGlAOaE68rEUy0IcK_bRFQUtH48gV4xoh5TKO70TMM39D00IyibGaib2oTdT";

$input = json_decode(file_get_contents('php://input'), true);
$orderID = $input['orderID'] ?? '';
if (!$orderID) {
    echo json_encode(['error' => 'No se recibió orderID']);
    exit;
}

// Obtener access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$secret");
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tokenResult = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($tokenResult, true);
$accessToken = $tokenData['access_token'] ?? '';
if (!$accessToken) {
    echo json_encode(['error' => 'No se pudo obtener access token']);
    exit;
}

// Capturar orden
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID/capture");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
