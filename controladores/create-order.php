<?php
header('Content-Type: application/json');

$clientId = "AQOeswWjLwmuqPv7EsgbJp6-Pq7tSQGjlr5A9PQTV6rKNmBTVZiH2YXKeQa8Ii5BGCkVmYOguvriaCC8";
$secret = "ENJSWgnMnDXbBg6ZyKh_RBGlAOaE68rEUy0IcK_bRFQUtH48gV4xoh5TKO70TMM39D00IyibGaib2oTd";

// Obtener access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$secret");
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tokenResult = curl_exec($ch);
if (!$tokenResult) {
    echo json_encode(['error' => 'No se pudo obtener token']);
    exit;
}
curl_close($ch);

$tokenData = json_decode($tokenResult, true);
$accessToken = $tokenData['access_token'] ?? '';
if (!$accessToken) {
    echo json_encode(['error' => 'No se pudo obtener access token']);
    exit;
}

// Obtener monto desde POST (opcional para mensual/anual)
$amount = $_POST['amount'] ?? "10.00";

// Crear orden
$data = [
    "intent" => "CAPTURE",
    "purchase_units" => [
        ["amount" => ["currency_code" => "USD", "value" => $amount]]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
