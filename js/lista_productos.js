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
