// Toda esta parte es js/lista_proveedores.js
// funcion para eliminar cliente
function setEliminarId(id) {
  const btn = document.getElementById("btnConfirmarEliminar");
  btn.href = "?eliminar=" + id;
}
// Rellenar modal de editar con datos del cliente
document
  .getElementById("modalEditar")
  .addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    // Obtener los datos del botón
    const id = button.getAttribute("data-id");
    const nombre = button.getAttribute("data-nombre");
    const dni = button.getAttribute("data-dni");
    const celular = button.getAttribute("data-celular");
    const email = button.getAttribute("data-email");
    const direccion = button.getAttribute("data-direccion");
    const departamento = button.getAttribute("data-departamento");
    const provincia = button.getAttribute("data-provincia");
    const distrito = button.getAttribute("data-distrito");

    // Insertar valores en el formulario
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-imagen").value = "";
    document.getElementById("edit-nombre").value = nombre;
    document.getElementById("edit-dni").value = dni;
    document.getElementById("edit-celular").value = celular;
    document.getElementById("edit-email").value = email;
    document.getElementById("edit-direccion").value = direccion;
    document.getElementById("edit-departamento").value = departamento;
    document.getElementById("edit-provincia").value = provincia;
    document.getElementById("edit-distrito").value = distrito;
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

//////////////////////////////////////////////////////////
/////////////////////////////////////////////////////
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#modalEditar form");
  const alerta = document.getElementById("alertaProveedor");

  form.addEventListener("submit", function (e) {
    alerta.classList.add("d-none");
    alerta.innerHTML = "";
    alerta.className = "alert d-none";

    let errores = [];

    const nombre = document.getElementById("edit-nombre").value.trim();
    const dni = document.getElementById("edit-dni").value.trim();
    const celular = document.getElementById("edit-celular").value.trim();
    const email = document.getElementById("edit-email").value.trim();
    const departamento = document.getElementById("edit-departamento").value;
    const imagen = document.getElementById("edit-imagen");

    if (nombre === "") errores.push("El nombre es obligatorio.");
    if (dni === "") errores.push("El documento es obligatorio.");
    if (celular === "") errores.push("El celular es obligatorio.");
    if (departamento === "" || departamento === "0")
      errores.push("Debe seleccionar un departamento.");

    // VALIDAR IMAGEN
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
