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
    document.getElementById("edit-sucursal").value = button.dataset.sucursal;
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
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#modalEditar form");
  const alerta = document.getElementById("alertaProducto");

  form.addEventListener("submit", function (e) {
    alerta.classList.add("d-none");
    alerta.innerHTML = "";
    alerta.className = "alert d-none";

    let errores = [];

    const nombre = document.getElementById("edit-nombre").value.trim();
    const codigo = document.getElementById("edit-codigo").value.trim();
    const precio = document.getElementById("edit-precio").value.trim();
    const costo = document.getElementById("edit-costo").value.trim();
    const stock = document.getElementById("edit-stock").value.trim();
    const proveedor = document.getElementById("edit-provedor").value;
    const categoria = document.getElementById("edit-categoria").value;
    const marca = document.getElementById("edit-marca").value;
    const sucursal = document.getElementById("edit-sucursal").value;
    const imagen = document.getElementById("edit-imagen");

    if (nombre === "") errores.push("El nombre es obligatorio.");
    if (codigo === "") errores.push("El SKU es obligatorio.");
    if (precio === "" || parseFloat(precio) <= 0)
      errores.push("El precio debe ser mayor a 0.");
    if (costo === "" || parseFloat(costo) < 0)
      errores.push("El costo no puede estar vacío.");
    if (stock === "" || !Number.isInteger(Number(stock)) || Number(stock) < 0)
      errores.push("El stock debe ser un número entero mayor o igual a 0.");

    //if (proveedor === "") errores.push("Debe seleccionar un proveedor.");
    if (categoria === "") errores.push("Debe seleccionar una categoría.");
    if (marca === "") errores.push("Debe seleccionar una marca.");
    if (sucursal === "") errores.push("Debe seleccionar una sucursal.");
    // VALIDAR IMAGEN (si se selecciona)
    if (imagen.files.length > 0) {
      const file = imagen.files[0];
      const tiposPermitidos = ["image/jpeg", "image/png"];
      const maxSize = 1.8 * 1024 * 1024;

      if (!tiposPermitidos.includes(file.type)) {
        errores.push("La imagen debe ser JPG o PNG.");
      }

      if (file.size > maxSize) {
        errores.push("La imagen no debe superar 1.8 MB.");
      }
    }

    if (errores.length > 0) {
      e.preventDefault();
      alerta.classList.remove("d-none");
      alerta.classList.add("alert-danger");
      alerta.innerHTML =
        "<ul class='mb-0'><li>" + errores.join("</li><li>") + "</li></ul>";
    }
  });
});

////Previsualizar imagen del modal
////Previsualizar imagen del modal
document.addEventListener("DOMContentLoaded", function () {
  const inputImagen = document.getElementById("edit-imagen");
  const previewCont = document.getElementById("previewImagen");
  const previewImg = document.getElementById("previewImg");
  const imgNombre = document.getElementById("imgNombre");
  const imgSize = document.getElementById("imgSize");
  const imgTipo = document.getElementById("imgTipo");

  inputImagen.addEventListener("change", function () {
    if (!this.files || this.files.length === 0) {
      previewCont.classList.add("d-none");
      return;
    }

    const file = this.files[0];

    // Validaciones básicas
    const tiposPermitidos = ["image/jpeg", "image/png"];
    if (!tiposPermitidos.includes(file.type)) {
      previewCont.classList.add("d-none");
      return;
    }

    // Mostrar datos
    //imgNombre.textContent = file.name;
    imgTipo.textContent = file.type;
    imgSize.textContent = (file.size / 1024).toFixed(2) + " KB";

    // Previsualizar imagen
    const reader = new FileReader();
    reader.onload = function (e) {
      previewImg.src = e.target.result;
      previewCont.classList.remove("d-none");
    };
    reader.readAsDataURL(file);
  });
});
