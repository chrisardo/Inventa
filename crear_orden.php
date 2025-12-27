<?php
header('Content-Type: application/json');

$clientId = "AQqH7WE5U9DmBsHG13aCHjuQmUzyRZkYvjoK3Pjfc80HeP_AJi-pZe1eTsjAaY0pHS5rF1m_Zoxu8fHE";
$secret   = "EMrQby7o8W7mgwOmcOzhXaPNrJtXB_rDTQMy9PIWrVdwUdKSVZvfw-V8MdHvpXGn3nQtVWgdldqcb2kF";

// Obtener token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$secret");
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Sandbox
$result = curl_exec($ch);
if (curl_errno($ch)) { echo json_encode(['error' => curl_error($ch)]); exit; }
curl_close($ch);

$tokenData = json_decode($result, true);
$accessToken = $tokenData['access_token'] ?? null;
if (!$accessToken) { echo json_encode(['error' => 'No se pudo obtener token']); exit; }

// Crear orden
$orderData = [
    "intent" => "CAPTURE",
    "purchase_units" => [
        ["amount" => ["currency_code" => "USD", "value" => "10.00"]]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Sandbox
$response = curl_exec($ch);
if(curl_errno($ch)){ echo json_encode(['error'=>curl_error($ch)]); exit; }
curl_close($ch);

echo $response;
