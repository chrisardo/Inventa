let charts = {};
Chart.register(ChartDataLabels);

/* ===================== CONFIGURACIÓN GLOBAL ===================== */
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

/* ===================== DATALABEL INTELIGENTE GLOBAL ===================== */
const dataLabelInteligente = {
  clip: false,
  font: {
    weight: "bold",
    size: 12,
  },
  formatter: (v) => `S/. ${Number(v).toLocaleString("es-PE")}`,

  color: (ctx) => {
    const value = ctx.dataset.data[ctx.dataIndex];
    const max = Math.max(...ctx.dataset.data);
    return value > max * 0.85 ? "#fff" : "#000";
  },

  anchor: (ctx) => {
    const value = ctx.dataset.data[ctx.dataIndex];
    const max = Math.max(...ctx.dataset.data);
    return value > max * 0.85 ? "center" : "end";
  },

  align: (ctx) => {
    const value = ctx.dataset.data[ctx.dataIndex];
    const max = Math.max(...ctx.dataset.data);
    const horizontal = ctx.chart.options.indexAxis === "y";

    if (value > max * 0.85) return "center";
    return horizontal ? "right" : "top";
  },

  offset: (ctx) => {
    const value = ctx.dataset.data[ctx.dataIndex];
    const max = Math.max(...ctx.dataset.data);
    return value > max * 0.85 ? 0 : 8;
  },
};

/* ===================== CARGAR TODOS LOS GRÁFICOS ===================== */
function cargarGraficos() {
  const anio = document.getElementById("anio").value;

  fetch(`controladores/procesar_graficos_index.php?anio=${anio}`)
    .then((res) => res.json())
    .then((data) => {
      crearComprasMes(data.compras_mes);
      crearComprasDia(data.compras_dia);
      crearTopProductos(data.top_productos);
      crearTopClientes(data.top_clientes);
      crearProductosMes(data.productos_mes);
      crearClientesMes(data.clientes_mes);
    });
}

/* ===================== DESTRUIR GRÁFICOS ===================== */
function destruir(id) {
  if (charts[id]) charts[id].destroy();
}

/* ===================== VENTAS POR MES ===================== */
function crearComprasMes(data) {
  destruir("compraMes");

  charts.compraMes = new Chart(document.getElementById("compraMes"), {
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
        datalabels: dataLabelInteligente,
      },
    },
  });
}

/* ===================== VENTAS POR DÍA ===================== */
function crearComprasDia(data) {
  destruir("compraDia");

  charts.compraDia = new Chart(document.getElementById("compraDia"), {
    type: "line",
    data: {
      labels: data.map((d) => d.dia),
      datasets: [
        {
          label: "Monto diario (S/.)",
          data: data.map((d) => d.total),
          tension: 0.3,
          fill: true,
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      scales: { y: { beginAtZero: true } },
      plugins: {
        datalabels: {
          ...dataLabelInteligente,
          align: "top",
        },
      },
    },
  });
}

/* ===================== TOP PRODUCTOS ===================== */
function crearTopProductos(data) {
  destruir("topProductos");

  charts.topProductos = new Chart(document.getElementById("topProductos"), {
    type: "bar",
    data: {
      labels: data.map((d) => d.nombre),
      datasets: [
        {
          label: "Total vendido (S/.)",
          data: data.map((d) => d.total),
          borderRadius: 10,
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      indexAxis: "y",
      scales: { x: { beginAtZero: true } },
      plugins: {
        datalabels: dataLabelInteligente,
      },
    },
  });
}

/* ===================== PRODUCTOS REGISTRADOS POR MES ===================== */
function crearProductosMes(data) {
  destruir("productosMes");

  charts.productosMes = new Chart(document.getElementById("productosMes"), {
    type: "bar",
    data: {
      labels: data.map((d) => nombresMeses[d.mes - 1]),
      datasets: [
        {
          label: "Productos registrados",
          data: data.map((d) => d.total),
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        datalabels: dataLabelInteligente,
      },
    },
  });
}

/* ===================== CLIENTES REGISTRADOS POR MES ===================== */
function crearClientesMes(data) {
  destruir("clientesMes");

  charts.clientesMes = new Chart(document.getElementById("clientesMes"), {
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
        datalabels: dataLabelInteligente,
      },
    },
  });
}

/* ===================== TOP CLIENTES ===================== */
function crearTopClientes(data) {
  destruir("topClientes");

  charts.topClientes = new Chart(document.getElementById("topClientes"), {
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
      scales: { x: { beginAtZero: true } },
      plugins: {
        datalabels: dataLabelInteligente,
      },
    },
  });
}

/* ===================== EVENTOS ===================== */
document.getElementById("anio").addEventListener("change", cargarGraficos);

/* ===================== INIT ===================== */
cargarGraficos();
