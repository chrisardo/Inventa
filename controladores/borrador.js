let charts = {};

function crearGrafico(id, tipo, labels, data, label) {
  if (charts[id]) charts[id].destroy();

  charts[id] = new Chart(document.getElementById(id), {
    type: tipo,
    data: {
      labels: labels,
      datasets: [
        {
          label: label,
          data: data,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: (ctx) => {
              let v = ctx.raw;
              return label.includes("S/.") ? `S/. ${v.toFixed(2)}` : v;
            },
          },
        },
      },
    },
  });
}

function cargarDashboard() {
  const anio = document.getElementById("anio").value;

  fetch(`controladores/api_dashboard.php?anio=${anio}`)
    .then((res) => res.json())
    .then((data) => {
      crearGrafico(
        "ventasMes",
        "bar",
        data.ventas_mes.map((x) => `Mes ${x.mes}`),
        data.ventas_mes.map((x) => Number(x.total)),
        "Monto S/."
      );

      crearGrafico(
        "cantidadMes",
        "line",
        data.cantidad_mes.map((x) => `Mes ${x.mes}`),
        data.cantidad_mes.map((x) => Number(x.cantidad)),
        "Cantidad"
      );

      crearGrafico(
        "topProductos",
        "pie",
        data.top_productos.map((x) => x.nombre),
        data.top_productos.map((x) => Number(x.total)),
        "Monto vendido (S/.)"
      );

      crearGrafico(
        "topClientes",
        "doughnut",
        data.top_clientes.map((x) => x.nombre),
        data.top_clientes.map((x) => Number(x.total)),
        "Monto comprado (S/.)"
      );

      crearGrafico(
        "clientesMes",
        "bar",
        data.clientes_mes.map((x) => `Mes ${x.mes}`),
        data.clientes_mes.map((x) => Number(x.total)),
        "Clientes"
      );

      crearGrafico(
        "clientesRubro",
        "pie",
        data.clientes_rubro.map((x) => x.nombre),
        data.clientes_rubro.map((x) => Number(x.total)),
        "Clientes"
      );
    });
}

document.getElementById("anio").addEventListener("change", cargarDashboard);
window.addEventListener("load", cargarDashboard);
