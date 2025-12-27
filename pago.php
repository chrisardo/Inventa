<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar con PayPal</title>
</head>
<body>
    <h1>Pagar con PayPal</h1>
    <div id="paypal-button-container"></div>

    <script src="https://www.paypal.com/sdk/js?client-id=AQqH7WE5U9DmBsHG13aCHjuQmUzyRZkYvjoK3Pjfc80HeP_AJi-pZe1eTsjAaY0pHS5rF1m_Zoxu8fHE&currency=USD"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return fetch('crear_orden.php', { method: 'POST' })
                    .then(res => res.json())
                    .then(order => {
                        if (!order.id) throw new Error(order.error || 'No se recibió orderID');
                        return order.id;
                    })
                    .catch(err => {
                        console.error('Error al crear orden:', err);
                        alert('Error al crear la orden. Revisa la consola.');
                    });
            },
            onApprove: function(data, actions) {
                return fetch('capturar_pago.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ orderID: data.orderID })
                })
                .then(res => res.json())
                .then(details => {
                    if (details.status === 'COMPLETED') alert('Pago completado');
                    else alert('Pago no completado: ' + JSON.stringify(details));
                })
                .catch(err => {
                    console.error('Error al capturar pago:', err);
                    alert('Error al capturar el pago. Revisa la consola.');
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
