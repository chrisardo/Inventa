// Toda esta parte es js/lista_productos.js
// funcion para eliminar producto
function setEliminarId(id) {
  const btn = document.getElementById("btnConfirmarEliminar");
  btn.href = "?eliminar=" + id;
}
// Rellenar modal de editar con datos del cliente
document
  .getElementById("modalEditar")
  .addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    document.getElementById("edit-id").value = button.dataset.id;
    document.getElementById("edit-nombre").value = button.dataset.nombre;
    document.getElementById("edit-codigo").value = button.dataset.codigo;
    document.getElementById("edit-stock").value = button.dataset.stock;
    document.getElementById("edit-precio").value = button.dataset.precio;
    document.getElementById("edit-costo").value = button.dataset.costo;
    document.getElementById("edit-descripcion").value =
      button.dataset.descripcion;

    document.getElementById("edit-categoria").value = button.dataset.categoria;
    document.getElementById("edit-provedor").value = button.dataset.provedor;

    // ✅ ESTO ES LO IMPORTANTE
    document.getElementById("edit-marca").value = button.dataset.marca;
  });

// Funcionalidades de búsqueda automática
document.addEventListener("DOMContentLoaded", function () {
  const inputBuscar = document.getElementById("inputBuscar");
  const formBuscar = document.getElementById("formBuscar");

  let delayTimer;

  inputBuscar.addEventListener("input", function () {
    clearTimeout(delayTimer);

    delayTimer = setTimeout(() => {
      formBuscar.submit();
    }, 300);
  });
});

/////////////////////////////////////////////////////
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#modalEditar form");
  const alerta = document.getElementById("alertaProducto");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    ocultarAlerta();

    // ===== VALIDAR CAMPOS VACÍOS =====
    const requeridos = [
      "nombre",
      "codigo",
      "precio",
      "costo",
      "stock",
      "categoria",
      "marca",
      "provedor",
    ];

    for (let name of requeridos) {
      const campo = form.querySelector(`[name="${name}"]`);
      if (!campo || campo.value.trim() === "") {
        mostrarError("Todos los campos son obligatorios.");
        return;
      }
    }

    // ===== VALIDAR SELECT =====
    const selects = form.querySelectorAll("select");
    for (let sel of selects) {
      if (sel.value === "") {
        mostrarError("Debe seleccionar todas las opciones.");
        return;
      }
    }

    // ===== VALIDAR STOCK ENTERO =====
    const stock = form.querySelector('[name="stock"]').value;
    if (!/^\d+$/.test(stock)) {
      mostrarError("El stock debe ser un número entero sin decimales.");
      return;
    }

    // ===== VALIDAR IMAGEN =====
    const imagen = form.querySelector('[name="imagen"]').files[0];
    if (imagen) {
      if (!["image/jpeg", "image/png"].includes(imagen.type)) {
        mostrarError("Solo se permiten imágenes JPG o PNG.");
        return;
      }
      if (imagen.size > 1.8 * 1024 * 1024) {
        mostrarError("La imagen no debe superar 1.8 MB.");
        return;
      }
    }

    // ===== ENVIAR POR AJAX =====
    const formData = new FormData(form);

    fetch("lista_productos.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.tipo !== "success") {
          mostrarError(data.mensaje);
          return;
        }

        // ✅ TODO CORRECTO → REDIRIGIR
        window.location.href = "lista_productos.php";
      })
      .catch(() => {
        mostrarError("Error inesperado del servidor.");
      });
  });

  function mostrarError(msg) {
    alerta.className = "alert alert-danger";
    alerta.textContent = msg;
    alerta.classList.remove("d-none");
  }

  function ocultarAlerta() {
    alerta.classList.add("d-none");
    alerta.textContent = "";
  }
});
