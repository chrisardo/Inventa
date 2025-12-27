<?php
// Verificar configuración SSL
echo "curl.cainfo: " . ini_get('curl.cainfo') . "<br>";
echo "openssl.cafile: " . ini_get('openssl.cafile') . "<br>";

// Verificar si el archivo existe
$cacert = ini_get('curl.cainfo');
if (file_exists($cacert)) {
    echo "✅ Archivo cacert.pem encontrado<br>";
} else {
    echo "❌ Archivo cacert.pem NO encontrado<br>";
}

// Probar conexión SSL básica
$url = 'https://api.twilio.com';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
$response = curl_exec($ch);

if ($response === false) {
    echo "❌ Error cURL: " . curl_error($ch);
} else {
    echo "✅ Conexión SSL exitosa";
}

curl_close($ch);
?>