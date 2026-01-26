/*Toda esta parte es de js/dashboards_ventas.js*/
let chartCompraMes,
  chartCantidadMes,
  chartTopProductos,
  chartTopCategorias,
  chartTopClientes;

Chart.defaults.font.size = 11;
Chart.defaults.font.family = "'Segoe UI', sans-serif";

function formatoNumero(valor) {
  return new Intl.NumberFormat("es-ES", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(valor);
}
function obtenerTop1Config(valores, colorNormal, colorTop) {
  const max = Math.max(...valores);

  return {
    max,
    background: valores.map((v) => (v === max ? colorTop : colorNormal)),
    borderColor: valores.map((v) => (v === max ? "#ff9800" : "transparent")),
    borderWidth: valores.map((v) => (v === max ? 2 : 0)),
  };
}

function animacionTop1(valorTop) {
  return {
    duration: 1200,
    easing: "easeOutBounce",
    delay: (ctx) => (ctx.raw === valorTop ? 300 : 0),
  };
}
function obtenerTop3Config(valores) {
  const ordenados = [...valores].sort((a, b) => b - a);
  const top1 = ordenados[0];
  const top2 = ordenados[1];
  const top3 = ordenados[2];

  return valores.map((v) => {
    if (v === top1) {
      return {
        icono: "🥇",
        color: "rgba(255,193,7,0.95)", // oro
        borde: "#ff9800",
        ancho: 3,
      };
    }
    if (v === top2) {
      return {
        icono: "🥈",
        color: "rgba(192,192,192,0.95)", // plata
        borde: "#bdbdbd",
        ancho: 2,
      };
    }
    if (v === top3) {
      return {
        icono: "🥉",
        color: "rgba(205,127,50,0.95)", // bronce
        borde: "#8d6e63",
        ancho: 2,
      };
    }
    return {
      icono: "",
      color: "rgba(150,150,150,0.6)",
      borde: "transparent",
      ancho: 0,
    };
  });
}

async function cargarGraficosYTablas() {
  const form = document.getElementById("formFiltros");
  const params = new URLSearchParams(new FormData(form));
  const res = await fetch(
    "./controladores/procesar_graficos_ventas.php?" + params.toString(),
  );
  const data = await res.json();

  const meses = [
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

  function actualizarChart(chart, ctx, config) {
    if (chart) {
      chart.destroy();
    }
    return new Chart(ctx, config);
  }

  /* ================= MONTO POR MES ================= */
  chartCompraMes = actualizarChart(
    chartCompraMes,
    document.getElementById("compraMes"),
    {
      type: "bar",
      data: {
        labels: meses,
        datasets: [
          {
            data: data.montoMes,
            backgroundColor: "rgba(54,162,235,0.75)",
            borderRadius: 6,
          },
        ],
      },
      options: {
        plugins: {
          legend: { display: false },
          datalabels: {
            anchor: "end",
            align: "end",
            formatter: formatoNumero,
            font: { size: 10 },
          },
          tooltip: {
            callbacks: {
              label: (ctx) => ` S/ ${formatoNumero(ctx.raw)}`,
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { font: { size: 10 } },
          },
          x: {
            ticks: { font: { size: 10 } },
          },
        },
      },
      plugins: [ChartDataLabels],
    },
  );

  /* ================= CANTIDAD POR MES ================= */
  chartCantidadMes = actualizarChart(
    chartCantidadMes,
    document.getElementById("cantidadMes"),
    {
      type: "bar",
      data: {
        labels: meses,
        datasets: [
          {
            data: data.cantMes,
            backgroundColor: "rgba(255,99,132,0.75)",
            borderRadius: 6,
          },
        ],
      },
      options: {
        plugins: {
          legend: { display: false },
          datalabels: {
            anchor: "end",
            align: "end",
            font: { size: 10 },
          },
        },
        scales: {
          y: { beginAtZero: true },
          x: { ticks: { font: { size: 10 } } },
        },
      },
      plugins: [ChartDataLabels],
    },
  );

  /* ================= TOP PRODUCTOS ================= */
  const cfgProd = obtenerTop1Config(
    data.topCant,
    "rgba(255,159,64,0.75)",
    "rgba(255,193,7,0.95)",
  );

  chartTopProductos = actualizarChart(
    chartTopProductos,
    document.getElementById("topProductos"),
    {
      type: "bar",
      data: {
        labels: data.topProductos.map((l, i) =>
          data.topCant[i] === cfgProd.max ? `🏆 ${l}` : l,
        ),
        datasets: [
          {
            data: data.topCant,
            backgroundColor: cfgProd.background,
            borderColor: cfgProd.borderColor,
            borderWidth: cfgProd.borderWidth,
            borderRadius: 8,
          },
        ],
      },
      options: {
        indexAxis: "y",
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => `S/ ${formatoNumero(ctx.raw)}`,
            },
          },
          datalabels: {
            anchor: "center",
            align: "center",
            color: (ctx) => (ctx.raw === cfgProd.max ? "#000" : "#fff"),
            formatter: (v) =>
              v === cfgProd.max
                ? `🏆 S/ ${formatoNumero(v)}`
                : `S/ ${formatoNumero(v)}`,
            font: (ctx) => ({
              size: ctx.raw === cfgProd.max ? 12 : 11,
              weight: "bold",
            }),
          },
        },
        scales: {
          x: { beginAtZero: true },
          y: { ticks: { font: { size: 10 } } },
        },
        animation: animacionTop1(cfgProd.max),
      },
      plugins: [ChartDataLabels],
    },
  );

  /* ================= TOP CATEGORÍAS ================= */
  const cfgCat = obtenerTop1Config(
    data.topCatCant,
    "rgba(75,192,192,0.75)",
    "rgba(40,167,69,0.95)",
  );

  chartTopCategorias = actualizarChart(
    chartTopCategorias,
    document.getElementById("topCategorias"),
    {
      type: "bar",
      data: {
        labels: data.topCategorias.map((l, i) =>
          data.topCatCant[i] === cfgCat.max ? `🥇 ${l}` : l,
        ),
        datasets: [
          {
            data: data.topCatCant,
            backgroundColor: cfgCat.background,
            borderColor: cfgCat.borderColor,
            borderWidth: cfgCat.borderWidth,
            borderRadius: 8,
          },
        ],
      },
      options: {
        indexAxis: "y",
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => `S/ ${formatoNumero(ctx.raw)}`,
            },
          },
          datalabels: {
            anchor: "center",
            align: "center",
            color: (ctx) => (ctx.raw === cfgCat.max ? "#000" : "#fff"),
            formatter: (v) =>
              v === cfgCat.max
                ? `🥇 S/ ${formatoNumero(v)}`
                : `S/ ${formatoNumero(v)}`,
            font: { size: 11, weight: "bold" },
          },
        },
        scales: {
          x: { beginAtZero: true },
          y: { ticks: { font: { size: 10 } } },
        },
        animation: animacionTop1(cfgCat.max),
      },
      plugins: [ChartDataLabels],
    },
  );

  /* ================= TOP CLIENTES ================= */
  const cfgCli = obtenerTop1Config(
    data.topCliTot,
    "rgba(153,102,255,0.75)",
    "rgba(111,66,193,0.95)",
  );

  chartTopClientes = actualizarChart(
    chartTopClientes,
    document.getElementById("topClientes"),
    {
      type: "bar",
      data: {
        labels: data.topClientes.map((l, i) =>
          data.topCliTot[i] === cfgCli.max ? `👑 ${l}` : l,
        ),
        datasets: [
          {
            data: data.topCliTot,
            backgroundColor: cfgCli.background,
            borderColor: cfgCli.borderColor,
            borderWidth: cfgCli.borderWidth,
            borderRadius: 8,
          },
        ],
      },
      options: {
        indexAxis: "y",
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => `S/ ${formatoNumero(ctx.raw)}`,
            },
          },
          datalabels: {
            anchor: "center",
            align: "center",
            color: (ctx) => (ctx.raw === cfgCli.max ? "#000" : "#fff"),
            formatter: (v) =>
              v === cfgCli.max
                ? `👑 S/ ${formatoNumero(v)}`
                : `S/ ${formatoNumero(v)}`,
            font: { size: 11, weight: "bold" },
          },
        },
        scales: {
          x: { beginAtZero: true },
          y: { ticks: { font: { size: 10 } } },
        },
        animation: animacionTop1(cfgCli.max),
      },
      plugins: [ChartDataLabels],
    },
  );

  /* ================= TABLAS ================= */
  function renderTabla(id, rows) {
    const container = document.getElementById(id);

    let html = `
    <table class="table table-sm table-bordered table-hover mb-0">
        <thead class="text-center">
            <tr>
                <th>Nombre</th>
                <th>Cant.</th>
                <th>Costo total</th>
                <th>Venta Total</th>
                <th>Rentab. %</th>
                <th>Utilidad</th>
            </tr>
        </thead>
        <tbody>`;

    rows.forEach((r) => {
      html += `
        <tr>
            <td>${r.nombre}</td>
            <td class="text-center">${r.cantidad}</td>
            <td class="text-end">${formatoNumero(r.costo_total)}</td>
            <td class="text-end fw-semibold">${formatoNumero(
              r.venta_total,
            )}</td>
            <td class="text-end ${
              r.rentabilidad < 0 ? "text-danger" : "text-success"
            }">
                ${r.rentabilidad}%
            </td>
            <td class="text-end fw-semibold">${formatoNumero(r.utilidad)}</td>
        </tr>`;
    });

    html += `</tbody></table>`;
    container.innerHTML = html;
  }

  renderTabla("tablaProductos", data.tablaProductos);
  renderTabla("tablaClientes", data.tablaClientes);
  renderTabla("tablaCategorias", data.tablaCategorias);
  /* ================= KPI ================= */
  const totalVentas = data.montoMes.reduce((a, b) => a + b, 0);
  const totalCantidad = data.cantMes.reduce((a, b) => a + b, 0);

  document.getElementById("kpiVenta").innerText =
    "S/ " + formatoNumero(totalVentas);

  document.getElementById("kpiCantidad").innerText = totalCantidad;

  document.getElementById("kpiTopProducto").innerText = data.topProductos[0]
    ? `🏆 ${data.topProductos[0]}`
    : "—";

  document.getElementById("kpiTopCliente").innerText = data.topClientes[0]
    ? `👑 ${data.topClientes[0]}`
    : "—";
  /* ================= GANANCIA O PÉRDIDA ================= */
  const diff = data.diferenciaGanancia;
  const kpiGanancia = document.getElementById("kpiGanancia_o_perdida");

  if (diff > 0) {
    kpiGanancia.innerHTML = `⬆️ +S/ ${formatoNumero(diff)}`;
    kpiGanancia.className = "fw-bold text-success";
  } else if (diff < 0) {
    kpiGanancia.innerHTML = `⬇️ -S/ ${formatoNumero(Math.abs(diff))}`;
    kpiGanancia.className = "fw-bold text-danger";
  } else {
    kpiGanancia.innerHTML = `➖ S/ 0.00`;
    kpiGanancia.className = "fw-bold text-secondary";
  }
}

cargarGraficosYTablas();
document
  .getElementById("formFiltros")
  .addEventListener("change", cargarGraficosYTablas);

////Exportar en PDF
document.getElementById("btnExportPDF").addEventListener("click", async () => {
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF("p", "mm", "a4");

  const fecha = new Date().toLocaleDateString("es-PE", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });

  /* ================= ENCABEZADO ================= */
  pdf.setFillColor(40, 167, 69);
  pdf.rect(0, 0, 210, 22, "F");

  pdf.setTextColor(255, 255, 255);
  pdf.setFontSize(14);
  pdf.text(EMPRESA_NOMBRE, 14, 14);

  pdf.setFontSize(9);
  pdf.text(`Reporte de Ventas`, 14, 19);
  pdf.text(`Fecha: ${fecha}`, 150, 14);
  pdf.text(`Usuario: ${USUARIO_NOMBRE}`, 150, 19);

  /* ================= CONTENIDO ================= */
  const dashboard = document.querySelector(".container-fluid.p-4");

  const canvas = await html2canvas(dashboard, {
    scale: 2,
    useCORS: true,
  });

  const imgData = canvas.toDataURL("image/png");

  const imgWidth = 190;
  const pageHeight = 297;
  const imgHeight = (canvas.height * imgWidth) / canvas.width;

  let heightLeft = imgHeight;
  let position = 30;

  pdf.addImage(imgData, "PNG", 10, position, imgWidth, imgHeight);
  heightLeft -= pageHeight;

  while (heightLeft > 0) {
    pdf.addPage();
    position = 10;
    pdf.addImage(imgData, "PNG", 10, position, imgWidth, imgHeight);
    heightLeft -= pageHeight;
  }

  pdf.save(`reporte_ventas_${fecha.replaceAll("/", "-")}.pdf`);
});

//Exportar excel
document.getElementById("btnExportExcel").addEventListener("click", () => {
  const wb = XLSX.utils.book_new();

  function tablaToSheet(id, nombreHoja) {
    const table = document.querySelector(`#${id} table`);
    if (!table) return;

    const ws = XLSX.utils.table_to_sheet(table);
    XLSX.utils.book_append_sheet(wb, ws, nombreHoja);
  }

  tablaToSheet("tablaProductos", "Productos");
  tablaToSheet("tablaClientes", "Clientes");
  tablaToSheet("tablaCategorias", "Categorias");

  XLSX.writeFile(wb, "reporte_ventas.xlsx");
});

///Exportar en PPT
document.getElementById("btnExportPPT").addEventListener("click", async () => {
  const pptx = new PptxGenJS();

  const fecha = new Date().toLocaleDateString("es-PE");
  const empresa = window.EMPRESA_NOMBRE || "Empresa";
  const usuario = window.USUARIO_NOMBRE || "Usuario";

  /* ================= SLIDE PORTADA ================= */
  let slide = pptx.addSlide();
  slide.background = { fill: "F5F5F5" };

  slide.addText(empresa, {
    x: 1,
    y: 2,
    w: 8,
    h: 1,
    fontSize: 28,
    bold: true,
    align: "center",
  });

  slide.addText("Reporte de Ventas", {
    x: 1,
    y: 3.2,
    w: 8,
    fontSize: 18,
    align: "center",
  });

  slide.addText(`Fecha: ${fecha}`, { x: 1, y: 4.2, w: 8, align: "center" });
  slide.addText(`Usuario: ${usuario}`, {
    x: 1,
    y: 4.7,
    w: 8,
    align: "center",
  });

  /* ================= FUNCIÓN CAPTURA ================= */
  async function agregarSlideDesdeElemento(
    selector,
    titulo,
    ancho = 9,
    alto = 5,
  ) {
    const elemento = document.querySelector(selector);
    if (!elemento) return;

    const canvas = await html2canvas(elemento, {
      scale: 2,
      backgroundColor: "#ffffff",
    });

    const img = canvas.toDataURL("image/png");

    const s = pptx.addSlide();
    s.addText(titulo, {
      x: 0.5,
      y: 0.3,
      fontSize: 16,
      bold: true,
    });

    s.addImage({
      data: img,
      x: 0.5,
      y: 0.9,
      w: ancho,
      h: alto,
    });
  }

  /* ================= SLIDES KPI ================= */
  await agregarSlideDesdeElemento("#kpiContainer", "Indicadores Clave (KPIs)");

  /* ================= SLIDES GRÁFICOS ================= */
  await agregarSlideDesdeElemento("#compraMes", "Monto Total por Mes");
  await agregarSlideDesdeElemento("#cantidadMes", "Cantidad Vendida por Mes");
  await agregarSlideDesdeElemento("#topProductos", "TOP 6 Productos");
  await agregarSlideDesdeElemento("#topCategorias", "TOP 6 Categorías");
  await agregarSlideDesdeElemento("#topClientes", "TOP 6 Clientes");

  /* ================= SLIDES TABLAS ================= */
  await agregarSlideDesdeElemento(
    "#tablaProductos",
    "Resumen de Productos",
    9,
    4,
  );
  await agregarSlideDesdeElemento(
    "#tablaClientes",
    "Resumen de Clientes",
    9,
    4,
  );
  await agregarSlideDesdeElemento(
    "#tablaCategorias",
    "Resumen de Categorías",
    9,
    4,
  );

  /* ================= EXPORTAR ================= */
  pptx.writeFile(`Reporte_Ventas_${fecha.replaceAll("/", "-")}.pptx`);
});
