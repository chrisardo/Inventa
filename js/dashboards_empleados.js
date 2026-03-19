document.addEventListener("DOMContentLoaded", function () {
  let chartMonto = null;
  let chartCantidad = null;
  let chartEmpleados = null;
  let chartTop = null;

  function formatearMoneda(valor) {
    return "S/ " + parseFloat(valor || 0).toFixed(2);
  }

  function destruirGraficos() {
    if (chartMonto) chartMonto.destroy();
    if (chartCantidad) chartCantidad.destroy();
    if (chartEmpleados) chartEmpleados.destroy();
    if (chartTop) chartTop.destroy();
  }

  function cargarGraficos() {
    destruirGraficos();

    // Limpiar tablas antes de volver a pintar
    document.getElementById("tablaProductos").innerHTML = "";
    document.getElementById("tablaEmpleados").innerHTML = "";

    const params = new URLSearchParams(
      new FormData(document.getElementById("formFiltros")),
    );

    fetch("controladores/procesar_graficos_empleados.php?" + params)
      .then((res) => res.json())
      .then((data) => {
        // ==============================
        // 🔥 TARJETA EJECUTIVA GLOBAL
        // ==============================

        if (data.resumenGlobal) {
          const margenClase =
            data.resumenGlobal.margen > 0
              ? "text-success"
              : data.resumenGlobal.margen < 0
                ? "text-danger"
                : "text-secondary";

          const htmlResumen = `
  <div class="col-md-4">
    <div class="card shadow-sm border-info">
      <div class="card-body text-center">
        <h6 class="text-muted">Total Vendido</h6>
        <h3 class="fw-bold text-primary">
          ${formatearMoneda(data.resumenGlobal.venta_total)}
        </h3>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm border-success">
      <div class="card-body text-center">
        <h6 class="text-muted">Utilidad Global</h6>
        <h3 class="fw-bold text-dark">
          ${formatearMoneda(data.resumenGlobal.utilidad)}
        </h3>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm border-success">
      <div class="card-body text-center">
        <h6 class="text-muted">Margen Bruto</h6>
        <h3 class="fw-bold ${margenClase}">
          ${data.resumenGlobal.margen}%
        </h3>
      </div>
    </div>
  </div>
  `;

          document.getElementById("resumenGlobal").innerHTML = htmlResumen;
        }
        // ==============================
        // 1️⃣ MONTO TOTAL POR MES
        // ==============================
        chartMonto = new Chart(document.getElementById("compraMes"), {
          type: "bar",
          data: {
            labels: [
              "Ene",
              "Feb",
              "Mar",
              "Abr",
              "May",
              "Jun",
              "Jul",
              "Ago",
              "Sep",
              "Oct",
              "Nov",
              "Dic",
            ],
            datasets: [
              {
                label: "Monto total",
                data: data.montoMes,
                backgroundColor: "#3498db",
                borderRadius: 8,
              },
            ],
          },
          options: {
            responsive: true,
            plugins: {
              datalabels: {
                color: "#000",
                anchor: "end",
                align: "top",
                formatter: (value) => formatearMoneda(value),
              },
            },
          },
          plugins: [ChartDataLabels],
        });

        // ==============================
        // 2️⃣ CANTIDAD POR MES
        // ==============================
        chartCantidad = new Chart(document.getElementById("cantidadMes"), {
          type: "line",
          data: {
            labels: [
              "Ene",
              "Feb",
              "Mar",
              "Abr",
              "May",
              "Jun",
              "Jul",
              "Ago",
              "Sep",
              "Oct",
              "Nov",
              "Dic",
            ],
            datasets: [
              {
                label: "Cantidad vendida",
                data: data.cantidadMes,
                borderColor: "#27ae60",
                backgroundColor: "rgba(39,174,96,0.2)",
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
              },
            ],
          },
          options: {
            responsive: true,
            plugins: {
              datalabels: {
                color: "#000",
                anchor: "end",
                align: "top",
                font: {
                  weight: "bold",
                },
                formatter: (value) => value, // 👈 muestra el número
              },
            },
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
          plugins: [ChartDataLabels],
        });

        // ==============================
        // 👨‍💼 EMPLEADOS REGISTRADOS POR MES
        // ==============================
        chartEmpleados = new Chart(document.getElementById("empleadosMes"), {
          type: "line",
          data: {
            labels: [
              "Ene",
              "Feb",
              "Mar",
              "Abr",
              "May",
              "Jun",
              "Jul",
              "Ago",
              "Sep",
              "Oct",
              "Nov",
              "Dic",
            ],
            datasets: [
              {
                label: "Empleados registrados",
                data: data.empleadosMes,
                borderColor: "#8e44ad",
                backgroundColor: "rgba(142,68,173,0.2)",
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
              },
            ],
          },
          options: {
            responsive: true,
            plugins: {
              datalabels: {
                color: "#000",
                anchor: "end",
                align: "top",
                font: {
                  weight: "bold",
                },
                formatter: (value) => value, // 👈 muestra el número
              },
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0, // evita decimales en empleados
                },
              },
            },
          },
          plugins: [ChartDataLabels],
        });

        // ==============================
        // 4️⃣ TOP EMPLEADOS
        // ==============================
        chartTop = new Chart(document.getElementById("topEmpleados"), {
          type: "bar",
          data: {
            labels: data.topNombres,
            datasets: [
              {
                data: data.topTotales,
                backgroundColor: [
                  "#f39c12",
                  "#27ae60",
                  "#8e44ad",
                  "#3498db",
                  "#e74c3c",
                  "#16a085",
                  "#2c3e50",
                ],
                borderRadius: 10,
                borderSkipped: false,
              },
            ],
          },
          options: {
            indexAxis: "y",
            responsive: true,
            plugins: {
              legend: { display: false },
              datalabels: {
                anchor: "center",
                align: "center",
                color: "#fff",
                font: { weight: "bold" },
                formatter: (value) => formatearMoneda(value),
              },
            },
            scales: {
              x: { beginAtZero: true },
            },
          },
          plugins: [ChartDataLabels],
        });

        // ==============================
        // 5️⃣ TABLA PRODUCTOS
        // ==============================
        let htmlProductos = `
<table class="table table-bordered table-hover table-sm">
<thead class="table-success text-center">
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Costo compra</th>
<th>Venta total</th>
<th>Rentabilidad %</th>
<th>Utilidad</th>
</tr>
</thead>
<tbody>
`;

        if (data.tablaProductos.length === 0) {
          htmlProductos += `
  <tr>
    <td colspan="6" class="text-center text-muted">
      No hay datos disponibles
    </td>
  </tr>
  `;
        } else {
          data.tablaProductos.forEach((p) => {
            const claseRentabilidad =
              p.rentabilidad > 0
                ? "text-success fw-bold"
                : p.rentabilidad < 0
                  ? "text-danger fw-bold"
                  : "text-secondary";

            htmlProductos += `
    <tr>
      <td>${p.nombre}</td>
      <td class="text-center">${p.cantidad}</td>
      <td class="text-end">S/ ${parseFloat(p.costo_compra).toFixed(2)}</td>
      <td class="text-end">S/ ${parseFloat(p.venta_total).toFixed(2)}</td>
      <td class="text-center ${claseRentabilidad}">
        ${p.rentabilidad}%
      </td>
      <td class="text-end">S/ ${parseFloat(p.utilidad).toFixed(2)}</td>
    </tr>
  `;
          });
        }

        htmlProductos += "</tbody></table>";
        document.getElementById("tablaProductos").innerHTML = htmlProductos;

        // ==============================
        // 6️⃣ TABLA EMPLEADOS
        // ==============================
        let htmlEmpleados = `
<table class="table table-bordered table-hover table-sm">
<thead class="table-primary text-center">
<tr>
<th>Empleado</th>
<th>Ventas</th>
<th>Cantidad</th>
<th>Venta total</th>
<th>Rentabilidad %</th>
<th>Utilidad</th>
</tr>
</thead>
<tbody>
`;

        if (data.tablaEmpleados.length === 0) {
          htmlEmpleados += `
  <tr>
    <td colspan="6" class="text-center text-muted">
      No hay datos disponibles
    </td>
  </tr>
  `;
        } else {
          data.tablaEmpleados.forEach((e, index) => {
            const claseRentabilidad =
              e.rentabilidad > 0
                ? "text-success fw-bold"
                : e.rentabilidad < 0
                  ? "text-danger fw-bold"
                  : "text-secondary";

            // 🏆 Medalla al mejor empleado (primero del ranking)
            const medalla = index === 0 ? " 🥇" : "";

            htmlEmpleados += `
    <tr ${index === 0 ? 'class="table-warning"' : ""}>
      <td>
        ${e.nombre}${medalla}
      </td>
      <td class="text-center">${e.ventas}</td>
      <td class="text-center">${e.cantidad}</td>
      <td class="text-end">S/ ${parseFloat(e.venta_total).toFixed(2)}</td>
      <td class="text-center ${claseRentabilidad}">
        ${e.rentabilidad}%
      </td>
      <td class="text-end">S/ ${parseFloat(e.utilidad).toFixed(2)}</td>
    </tr>
  `;
          });
        }

        htmlEmpleados += "</tbody></table>";
        document.getElementById("tablaEmpleados").innerHTML = htmlEmpleados;
      })
      .catch((error) => {
        console.error("Error al cargar datos:", error);
      });
  }

  // Cargar al inicio
  cargarGraficos();

  // Recargar al cambiar filtros
  document
    .getElementById("formFiltros")
    .addEventListener("change", cargarGraficos);
});
// ==============================
// 📄 EXPORTAR REPORTE EMPLEADOS EN PDF (COMPLETO)
// ==============================
document.getElementById("btnPdf").addEventListener("click", async () => {
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF("p", "mm", "a4");

  const dashboard = document.getElementById("reporteCompleto");

  if (!dashboard) {
    alert("No se encontró el contenedor del reporte.");
    return;
  }

  // ==============================
  // 🔥 OCULTAR BOTONES
  // ==============================
  const botones = document.querySelectorAll(".no-export");
  botones.forEach((btn) => (btn.style.display = "none"));

  refrescarCharts();
  await new Promise((r) => setTimeout(r, 400));

  const canvas = await html2canvas(dashboard, {
    scale: 2,
    useCORS: true,
    backgroundColor: "#ffffff",
    windowWidth: dashboard.scrollWidth,
    windowHeight: dashboard.scrollHeight,
  });

  const imgData = canvas.toDataURL("image/png");

  const pageWidth = 210;
  const pageHeight = 297;

  const marginTop = 20;
  const marginX = 10;
  const usableHeight = pageHeight - marginTop;

  const imgWidth = pageWidth - marginX * 2;
  const imgHeight = (canvas.height * imgWidth) / canvas.width;

  let y = 0;

  while (y < imgHeight) {
    if (y > 0) pdf.addPage();

    pdf.addImage(imgData, "PNG", marginX, marginTop - y, imgWidth, imgHeight);

    y += usableHeight;
  }

  pdf.save("reporte_empleados.pdf");

  // ==============================
  // 🔥 VOLVER A MOSTRAR BOTONES
  // ==============================
  botones.forEach((btn) => (btn.style.display = ""));
});

// ==============================
// 🔄 REFRESCAR CHARTS
// ==============================
function refrescarCharts() {
  if (window.Chart && Chart.instances) {
    Object.values(Chart.instances).forEach((chart) => {
      chart.resize();
      chart.update("none"); // sin animación
    });
  }
}
// ==============================
// 📊 EXPORTAR REPORTE EMPLEADOS EN PPT
// ==============================
document.getElementById("btnPpt").addEventListener("click", async () => {
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

  slide.addText("Reporte Estadístico de Empleados", {
    x: 1,
    y: 3.2,
    w: 8,
    fontSize: 18,
    align: "center",
  });

  slide.addText(`Fecha: ${fecha}`, {
    x: 1,
    y: 4.2,
    w: 8,
    align: "center",
  });

  slide.addText(`Generado por: ${usuario}`, {
    x: 1,
    y: 4.7,
    w: 8,
    align: "center",
  });

  /* ================= FUNCIÓN PARA CAPTURAR ================= */
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
      useCORS: true,
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

  /* ================= SLIDE KPI ================= */
  await agregarSlideDesdeElemento("#resumenGlobal", "Indicadores Clave (KPIs)");

  /* ================= SLIDES GRÁFICOS ================= */
  await agregarSlideDesdeElemento("#compraMes", "Monto Total por Mes");

  await agregarSlideDesdeElemento("#cantidadMes", "Cantidad Vendida por Mes");

  await agregarSlideDesdeElemento(
    "#empleadosMes",
    "Empleados Registrados por Mes",
  );

  await agregarSlideDesdeElemento(
    "#topEmpleados",
    "Top 7 Empleados que Más Venden",
  );

  /* ================= SLIDES TABLAS ================= */
  await agregarSlideDesdeElemento(
    "#tablaProductos",
    "Resumen de Productos",
    9,
    4,
  );

  await agregarSlideDesdeElemento(
    "#tablaEmpleados",
    "Resumen de Empleados",
    9,
    4,
  );

  /* ================= EXPORTAR ================= */
  pptx.writeFile(`Reporte_Empleados_${fecha.replaceAll("/", "-")}.pptx`);
});
