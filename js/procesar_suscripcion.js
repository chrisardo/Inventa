// Botón mensual
paypal
  .Buttons({
    createOrder: function (data, actions) {
      return fetch("../controladores/create-order.php", {
        method: "POST",
        body: new URLSearchParams({ amount: "11.00" }),
      })
        .then((res) => res.json())
        .then((order) => {
          if (!order.id) throw new Error("Error al crear la orden");
          return order.id;
        });
    },
    onApprove: function (data, actions) {
      return fetch("./controladores/capture-order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ orderID: data.orderID }),
      })
        .then((res) => res.json())
        .then((details) => {
          if (details.status === "COMPLETED") alert("Pago mensual completado");
          else alert("Error: " + JSON.stringify(details));
        });
    },
  })
  .render("#paypal-mensual");

// Botón anual
paypal
  .Buttons({
    createOrder: function (data, actions) {
      return fetch("./controladores/create-order.php", {
        method: "POST",
        body: new URLSearchParams({ amount: "110.00" }),
      })
        .then((res) => res.json())
        .then((order) => {
          if (!order.id) throw new Error("Error al crear la orden");
          return order.id;
        });
    },
    onApprove: function (data, actions) {
      return fetch("../controladores/capture-order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ orderID: data.orderID }),
      })
        .then((res) => res.json())
        .then((details) => {
          if (details.status === "COMPLETED") alert("Pago anual completado");
          else alert("Error: " + JSON.stringify(details));
        });
    },
  })
  .render("#paypal-button-container");
