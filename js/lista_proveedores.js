// Todo esto es js/lista_proveedores.js
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


