//Toda esta parte es js/modal_lista_productos_ventas.php
/*****************************************************
 * LISTA DE PRODUCTOS (MODAL)
 *****************************************************/

document.addEventListener("DOMContentLoaded", function () {
  const inputBuscar = document.getElementById("inputBuscar");
  const tabla = document.getElementById("tablaProductos");
  const totalRegistros = document.getElementById("totalRegistros");
  const btnAnterior = document.getElementById("btnAnterior");
  const btnSiguiente = document.getElementById("btnSiguiente");
  const paginaActualSpan = document.getElementById("paginaActual");
  const totalPaginasSpan = document.getElementById("totalPaginas");

  let pagina = 1;
  let buscar = "";

  function cargarProductos() {
    fetch("controladores/buscar_productos.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `buscar=${encodeURIComponent(buscar)}&pagina=${pagina}`,
    })
      .then((res) => res.json())
      .then((data) => {
        tabla.innerHTML = data.html;
        totalRegistros.innerText = data.total;
        paginaActualSpan.innerText = pagina;
        totalPaginasSpan.innerText = data.totalPaginas;
        btnAnterior.disabled = pagina <= 1;
        btnSiguiente.disabled = pagina >= data.totalPaginas;
      });
  }

  cargarProductos();

  inputBuscar.addEventListener("keyup", function () {
    buscar = this.value;
    pagina = 1;
    cargarProductos();
  });

  btnAnterior.addEventListener("click", function () {
    if (pagina > 1) {
      pagina--;
      cargarProductos();
    }
  });

  btnSiguiente.addEventListener("click", function () {
    if (pagina < parseInt(totalPaginasSpan.innerText)) {
      pagina++;
      cargarProductos();
    }
  });
});

/*****************************************************
 * CARRITO
 *****************************************************/

function agregarAlCarrito(idProducto) {
  fetch("controladores/agregar_carrito_ajax.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "idProducto=" + idProducto,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "ok") cargarCarrito();
    });
}

function cargarCarrito() {
  fetch("controladores/cargar_carrito.php")
    .then((res) => res.json())
    .then((data) => {
      document.getElementById("tablaCarrito").innerHTML = data.html;
      document.getElementById("totalVenta").innerText = data.total;
      validarCarrito();
    });
}

function validarCarrito() {
  const inputs = document.querySelectorAll(".cantidadInput");
  const alerta = document.getElementById("alertaStock");
  const btnVender = document.getElementById("btnVender");
  const mensajeVacio = document.getElementById("mensajeCarritoVacio");

  let hayError = false;
  //let carritoVacio = true;
  let carritoVacio = inputs.length === 0;
  inputs.forEach((input) => {
    const max = parseInt(input.getAttribute("max")) || 0;
    const val = parseInt(input.value) || 0;

    if (val > 0) carritoVacio = false;

    if (val > max) {
      hayError = true;
      input.classList.add("is-invalid");
    } else {
      input.classList.remove("is-invalid");
    }
  });

  alerta.classList.toggle("d-none", !hayError);
  // 🛒 Mostrar / ocultar mensaje de carrito vacío
  mensajeVacio.classList.toggle("d-none", !carritoVacio);

  btnVender.disabled = hayError || carritoVacio;
  btnVender.style.pointerEvents = btnVender.disabled ? "none" : "auto";
  btnVender.classList.toggle("opacity-50", btnVender.disabled);
}

document.addEventListener("DOMContentLoaded", cargarCarrito);

function eliminarProducto(idProducto) {
  fetch("controladores/eliminar_producto_carrito.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "idProducto=" + idProducto,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "ok") cargarCarrito();
    });
}

function actualizarCantidad(idProducto, cantidad) {
  fetch("controladores/actualizar_cantidad_carrito.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `idProducto=${idProducto}&cantidad=${cantidad}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "ok") cargarCarrito();
    });
}

/*****************************************************
 * MODAL REGISTRAR VENTA
 *****************************************************/

document.addEventListener("DOMContentLoaded", () => {
  const btnVender = document.getElementById("btnVender");

  btnVender.addEventListener("click", () => {
    if (btnVender.disabled) return;

    const total =
      parseFloat(document.getElementById("totalVenta").innerText) || 0;
    if (total <= 0) return;

    document.getElementById("totalModal").innerText = total.toFixed(2);
    document.getElementById("pago").value = "";
    document.getElementById("vuelto").innerText = "0.00";
    document.getElementById("mensajePago").classList.add("d-none");
    document.getElementById("btnConfirmarVenta").disabled = true;

    new bootstrap.Modal(document.getElementById("modalVenta")).show();
  });

  document.getElementById("pago").addEventListener("input", calcularVuelto);
  document
    .getElementById("idCliente")
    .addEventListener("change", calcularVuelto);
  document
    .getElementById("formaPago")
    .addEventListener("change", calcularVuelto);
});

/*****************************************************
 * VALIDACIONES
 *****************************************************/

function validarClienteFormaPago() {
  const idCliente = document.getElementById("idCliente").value;
  const formaPago = document.getElementById("formaPago").value;
  const mensaje = document.getElementById("mensajePago");

  if (!idCliente || !formaPago) {
    mensaje.innerText = "⚠️ Debe seleccionar un cliente y una forma de pago";
    mensaje.classList.remove("d-none");
    return false;
  }

  mensaje.classList.add("d-none");
  return true;
}

function calcularVuelto() {
  const total =
    parseFloat(document.getElementById("totalModal").innerText) || 0;
  const pago = parseFloat(document.getElementById("pago").value) || 0;
  const vueltoSpan = document.getElementById("vuelto");
  const mensaje = document.getElementById("mensajePago");
  const btnConfirmar = document.getElementById("btnConfirmarVenta");

  if (!validarClienteFormaPago()) {
    btnConfirmar.disabled = true;
    vueltoSpan.innerText = "0.00";
    return;
  }

  if (pago < total) {
    mensaje.innerText = "⚠️ El pago del cliente es menor al total de la venta";
    mensaje.classList.remove("d-none");
    vueltoSpan.innerText = "0.00";
    btnConfirmar.disabled = true;
    return;
  }

  vueltoSpan.innerText = (pago - total).toFixed(2);
  mensaje.classList.add("d-none");
  btnConfirmar.disabled = false;
}
/*****************************************************
 * CONFIRMAR VENTA
 *****************************************************/

document.getElementById("btnConfirmarVenta").addEventListener("click", () => {
  const data = new FormData();

  data.append("total", document.getElementById("totalModal").innerText);
  data.append("pago", document.getElementById("pago").value);
  data.append("vuelto", document.getElementById("vuelto").innerText);
  data.append("idCliente", document.getElementById("idCliente").value);
  data.append("formaPago", document.getElementById("formaPago").value);

  fetch("controladores/registrar_venta.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.json())
    .then((resp) => {
      if (resp.status === "ok") {
        alert("✅ Venta registrada correctamente");
        bootstrap.Modal.getInstance(
          document.getElementById("modalVenta")
        ).hide();
        cargarCarrito();
      } else {
        alert("❌ " + resp.msg);
      }
    });
});
