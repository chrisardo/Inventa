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
    `./controladores/procesar_graficos_producto.php?anio=${anio}&producto=${producto}&categoria=${categoria}`,
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
    },
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

////Exportar en PDF
document.getElementById("btnExportPDF").addEventListener("click", async () => {
  const { jsPDF } = window.jspdf;

  const elemento = document.getElementById("reporteProductos");

  if (!elemento) {
    alert("No se encontró el contenedor del reporte.");
    return;
  }

  const empresa = NOMBRE_EMPRESA || "Empresa";
  const fecha = new Date().toLocaleDateString("es-PE");

  await new Promise((resolve) => setTimeout(resolve, 500));

  const canvas = await html2canvas(elemento, {
    scale: 2,
    useCORS: true,
    backgroundColor: "#ffffff",
  });

  const imgData = canvas.toDataURL("image/png");

  const pdf = new jsPDF("p", "mm", "a4");

  const pageWidth = 210;
  const pageHeight = 297;

  /* ===== ENCABEZADO ===== */
  pdf.setFont("helvetica", "bold");
  pdf.setFontSize(16);
  pdf.text(empresa, pageWidth / 2, 15, { align: "center" });

  pdf.setFont("helvetica", "normal");
  pdf.setFontSize(11);
  pdf.text(`Reporte gráfico de productos`, pageWidth / 2, 22, {
    align: "center",
  });

  pdf.text(`Fecha de exportación: ${fecha}`, pageWidth / 2, 28, {
    align: "center",
  });

  /* ===== IMAGEN DEL REPORTE ===== */
  const imgWidth = pageWidth - 20;
  const imgHeight = (canvas.height * imgWidth) / canvas.width;

  let position = 35;
  let heightLeft = imgHeight;

  pdf.addImage(imgData, "PNG", 10, position, imgWidth, imgHeight);
  heightLeft -= pageHeight - position;

  while (heightLeft > 0) {
    position = heightLeft - imgHeight + 35;
    pdf.addPage();
    pdf.addImage(imgData, "PNG", 10, position, imgWidth, imgHeight);
    heightLeft -= pageHeight - 20;
  }

  pdf.save(`Reporte_productos_${fecha.replaceAll("/", "-")}.pdf`);
});

///Exportar en PPT
document.getElementById("btnExportPPT").addEventListener("click", async () => {
  const pptx = new PptxGenJS();
  const fecha = new Date().toLocaleDateString("es-PE");

  const empresa = NOMBRE_EMPRESA || "Empresa";

  /* ========= PORTADA ========= */
  let portada = pptx.addSlide();
  portada.background = { fill: "F5F5F5" };

  portada.addText(empresa, {
    x: 1,
    y: 2,
    w: 8,
    fontSize: 28,
    bold: true,
    align: "center",
  });

  portada.addText("Reporte gráfico de productos", {
    x: 1,
    y: 3.2,
    w: 8,
    fontSize: 18,
    align: "center",
  });

  portada.addText(`Fecha: ${fecha}`, {
    x: 1,
    y: 4.2,
    w: 8,
    align: "center",
  });

  /* ========= FUNCIÓN PARA CAPTURAR GRÁFICOS ========= */
  async function agregarGrafico(canvasId, titulo) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const imgData = canvas.toDataURL("image/png", 1.0);

    const slide = pptx.addSlide();

    slide.addText(titulo, {
      x: 0.5,
      y: 0.3,
      fontSize: 16,
      bold: true,
    });

    slide.addImage({
      data: imgData,
      x: 0.5,
      y: 1,
      w: 9,
      h: 5,
    });
  }

  /* ========= AGREGAR TODOS LOS GRÁFICOS ========= */
  await agregarGrafico("productosMes", "Productos registrados por mes");
  await agregarGrafico("productoDia", "Productos registrados por día");
  await agregarGrafico("topProductos", "Top 6 productos más vendidos");
  await agregarGrafico("topCategorias", "Top 6 categorías más vendidas");
  await agregarGrafico("productosPorCategoria", "Productos por categoría");
  await agregarGrafico("compraMes", "Monto total vendido por mes");

  /* ========= EXPORTAR ========= */
  pptx.writeFile(`Reporte_productos_${fecha.replaceAll("/", "-")}.pptx`);
});
