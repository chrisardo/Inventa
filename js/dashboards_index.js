//Toda esta parte es de js/dashboards_index.js
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
      crearComprasSemana(data.compras_semana);
      crearComprasDia(data.compras_dia);
      crearTopProductos(data.top_productos);
      crearTopClientes(data.top_clientes);
      crearTopVendedores(data.top_vendedores);
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
/* ===================== VENTAS POR SEMANA ===================== */
function crearComprasSemana(data) {
  destruir("compraSemana");

  charts.compraSemana = new Chart(document.getElementById("compraSemana"), {
    type: "line",
    data: {
      labels: data.map((d) => "Semana " + d.semana),
      datasets: [
        {
          label: "Ventas por semana (S/.)",
          data: data.map((d) => d.total),
          tension: 0.4,
          fill: false,
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true },
      },
      plugins: {
        datalabels: dataLabelInteligente,
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
          label: "Unidades ingresadas",
          data: data.map((d) => d.total),
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        datalabels: {
          font: { weight: "bold", size: 12 },
          //formatter: (v) => Number(v).toLocaleString("es-PE"),
          anchor: "end",
          align: "top",
          offset: 6,
        },
      },
      scales: {
        y: { beginAtZero: true },
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
        datalabels: {
          font: { weight: "bold", size: 12 },
          formatter: (v) => Number(v).toLocaleString("es-PE"),
          anchor: "end",
          align: "top",
          offset: 6,
        },
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
/* ===================== TOP VENDEDORES ===================== */
function crearTopVendedores(data) {
  destruir("topVendedores");

  charts.topVendedores = new Chart(document.getElementById("topVendedores"), {
    type: "bar",
    data: {
      labels: data.map((d) => d.nombre + " " + d.apellido),
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
function cargarKPI() {
  const anio = document.getElementById("anio").value;

  fetch(`controladores/procesar_kpi_index.php?anio=${anio}`)
    .then((res) => res.json())
    .then((data) => {
      actualizarKPI("kpi-total-ventas", Number(data.totalVentas) || 0, true);
      actualizarKPI(
        "kpi-total-ventas-dia",
        Number(data.totalVentasDia) || 0,
        true,
      );
      actualizarKPI("kpi-ganancia", Number(data.ganancia) || 0, true);
      actualizarKPI("kpi-clientes", Number(data.totalClientes) || 0, false);
      actualizarKPI("kpi-productos", Number(data.totalProductos) || 0, false);
      actualizarKPI("kpi-empleados", Number(data.totalEmpleados) || 0, false);

      // 🔥 CAMBIAR COLOR DINÁMICAMENTE
      const header = document.getElementById("card-ganancia-header");

      if (data.ganancia >= 0) {
        header.classList.remove("bg-danger");
        header.classList.add("bg-success");
      } else {
        header.classList.remove("bg-success");
        header.classList.add("bg-danger");
      }

      // 🔥 CAMBIAR ICONO
      const icono = document.getElementById("icono-ganancia");
      icono.className =
        data.ganancia >= 0
          ? "fas fa-arrow-up text-success ms-2"
          : "fas fa-arrow-down text-danger ms-2";
    });
}

function actualizarKPI(id, valor) {
  const el = document.getElementById(id);
  el.dataset.value = valor;
  animarKPI(el, valor);
}
/* ===================== EVENTOS ===================== */
// document.getElementById("anio").addEventListener("change", cargarGraficos);
/*document.getElementById("anio").addEventListener("change", function () {
  document.getElementById("formFiltros").submit();
});*/
document.getElementById("anio").addEventListener("change", () => {
  cargarKPI();
  cargarGraficos();
});
/* ===================== INIT ===================== */
cargarKPI();
cargarGraficos();
