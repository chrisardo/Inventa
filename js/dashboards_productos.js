//Toda esta parte es js/dashboards_productos.js
let charts = {};
Chart.register(ChartDataLabels);

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

function cargarGraficos() {
  const anio = document.getElementById("anio").value;
  const producto = document.getElementById("producto").value;
  const categoria = document.getElementById("categoria").value;

  fetch(
    `./controladores/procesar_graficos_producto.php?anio=${anio}&producto=${producto}&categoria=${categoria}`
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("TOP CATEGORIAS:", data.top_categorias);
      crearProductosMes(data.productos_mes);
      crearProductosDia(data.productos_dia);
      crearComprasMes(data.compras_mes);
      crearTopProductos(data.top_productos);
      crearProductosCategoria(data.productos_categoria);
      crearTopCategorias(data.top_categorias);
    });
}

function destruir(id) {
  if (charts[id]) charts[id].destroy();
}

/* PRODUCTOS POR MES */
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
        datalabels: { anchor: "end", align: "top" },
      },
    },
  });
}

/* PRODUCTOS POR DÍA */
function crearProductosDia(data) {
  destruir("productoDia");

  charts.productoDia = new Chart(document.getElementById("productoDia"), {
    type: "line",
    data: {
      labels: data.map((d) => d.dia),
      datasets: [
        {
          label: "Productos registrados",
          data: data.map((d) => d.total),
          tension: 0.3,
        },
      ],
    },
  });
}

/* VENTAS POR MES */
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
        datalabels: {
          anchor: "end",
          align: "top",
          formatter: (v) => `S/. ${v}`,
        },
      },
    },
  });
}

/* TOP PRODUCTOS */
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

/* PRODUCTOS POR CATEGORÍA */
function crearProductosCategoria(data) {
  destruir("productosPorCategoria");

  charts.productosPorCategoria = new Chart(
    document.getElementById("productosPorCategoria"),
    {
      type: "doughnut",
      data: {
        labels: data.map((d) => d.categoria),
        datasets: [
          {
            data: data.map((d) => d.total),
          },
        ],
      },
      plugins: [ChartDataLabels],
      options: {
        plugins: {
          datalabels: {
            formatter: (v) => v,
          },
        },
      },
    }
  );
}
/* TOP 6 CATEGORÍAS MÁS VENDIDAS */
function crearTopCategorias(data) {
  destruir("topCategorias");

  charts.topCategorias = new Chart(document.getElementById("topCategorias"), {
    type: "bar",
    data: {
      labels: data.map((d) => d.categoria),
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
      plugins: {
        datalabels: {
          anchor: "end",
          align: "right",
          formatter: (v) => `S/. ${v}`,
        },
      },
      scales: {
        x: { beginAtZero: true },
      },
    },
  });
}

["anio", "producto", "categoria"].forEach((id) => {
  document.getElementById(id).addEventListener("change", cargarGraficos);
});

cargarGraficos();
