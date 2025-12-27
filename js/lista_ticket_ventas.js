// js/lista_clientes.js
// funcion para eliminar cliente
function setEliminarId(id) {
  const btn = document.getElementById("btnConfirmarEliminar");
  btn.href = "?eliminar=" + id;
}


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
