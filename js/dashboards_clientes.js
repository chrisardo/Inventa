//Esto es js/dashboards_clientes.js
let charts = {};
Chart.register(ChartDataLabels);

/* ===================== MESES ===================== */
const nombresMeses = [
  "Enero",
  "Febrero",
  "Marzo",
  "Abril",
  "Mayo",
  "Junio",
  "Julio",
  "Agosto",
  "Setiembre",
  "Octubre",
  "Noviembre",
  "Diciembre",
];

/* ===================== CARGAR ===================== */
function cargarGraficos() {
  const anio = document.getElementById("anio").value;
  const cliente = document.getElementById("cliente").value;
  const rubro = document.getElementById("rubro").value;

  fetch(
    `controladores/procesar_graficos_cliente.php?anio=${anio}&cliente=${cliente}&rubro=${rubro}`
  )
    .then((res) => res.json())
    .then((data) => {
      crearClientesMes(data.clientes_mes);
      crearClientesDia(data.clientes_dia);
      crearComprasMes(data.compras_mes);
      crearTopClientes(data.top_clientes);
      crearClientesRubro(data.clientes_rubro);
    });
}

/* ===================== UTIL ===================== */
function destruir(id) {
  if (charts[id]) charts[id].destroy();
}

/* ===================== CLIENTES / MES ===================== */
function crearClientesMes(data) {
  destruir("clientesMes");

  charts.clientesMes = new Chart(clientesMes, {
    type: "bar",
    data: {
      labels: data.map((d) => nombresMeses[d.mes - 1]),
      datasets: [
        {
          label: "Clientes registrados",
          data: data.map((d) => d.total),
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        datalabels: { anchor: "end", align: "top" },
      },
    },
  });
}

/* ===================== CLIENTES / DIA ===================== */
function crearClientesDia(data) {
  destruir("clienteDia");

  charts.clienteDia = new Chart(clienteDia, {
    type: "line",
    data: {
      labels: data.map((d) => d.dia),
      datasets: [
        {
          label: "Clientes registrados",
          data: data.map((d) => d.total),
          tension: 0.3,
        },
      ],
    },
  });
}

/* ===================== COMPRAS / MES ===================== */
function crearComprasMes(data) {
  destruir("compraMes");

  charts.compraMes = new Chart(compraMes, {
    type: "bar",
    data: {
      labels: data.map((d) => nombresMeses[d.mes - 1]),
      datasets: [
        {
          label: "Monto total (S/.)",
          data: data.map((d) => d.total),
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        datalabels: {
          anchor: "end",
          align: "top",
          formatter: (v) => `S/. ${v}`,
        },
      },
    },
  });
}

/* ===================== TOP CLIENTES ===================== */
function crearTopClientes(data) {
  destruir("topClientes");

  charts.topClientes = new Chart(topClientes, {
    type: "bar",
    data: {
      labels: data.map((d) => d.nombre),
      datasets: [
        {
          label: "Total comprado (S/.)",
          data: data.map((d) => d.total),
          borderRadius: 10,
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      indexAxis: "y",
      plugins: {
        datalabels: {
          anchor: "end",
          align: "right",
          formatter: (v) => `S/. ${v}`,
        },
      },
      scales: { x: { beginAtZero: true } },
    },
  });
}

/* ===================== CLIENTES / RUBRO ===================== */
function crearClientesRubro(data) {
  destruir("clientesPorRubro");

  charts.clientesPorRubro = new Chart(clientesPorRubro, {
    type: "doughnut",
    data: {
      labels: data.map((d) => d.rubro),
      datasets: [{ data: data.map((d) => d.total) }],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: { datalabels: { formatter: (v) => v } },
    },
  });
}

/* ===================== EVENTOS ===================== */
["anio", "cliente", "rubro"].forEach((id) =>
  document.getElementById(id).addEventListener("change", cargarGraficos)
);

cargarGraficos();
