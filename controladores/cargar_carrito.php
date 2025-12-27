<?php
//esta parte es controladores/cargar_carrito.php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];

$sql = "SELECT c.*, p.nombre, p.precio, p.imagen, p.stock
        FROM carrito_venta c
        INNER JOIN producto p ON c.idProducto = p.idProducto
        WHERE c.id_user = '$usId'";

$resultado = mysqli_query($conexion, $sql);

$html = "";
$totalVenta = 0;

while ($row = mysqli_fetch_assoc($resultado)) {
  $stockDisponible = $row['stock']; // ✅ stock real del producto
  //$cantidad = $row['cantidad'];

  $subtotal = $row['precioTotal'] * $row['cantidad'];
  $totalVenta += $subtotal;
  // ✅ VALIDACIÓN DE STOCK
  $stockInsuficiente = ($row['cantidad'] > $stockDisponible) ? true : false;

  $html .= "
      <tr class='" . ($stockInsuficiente ? "table-danger" : "") . "'>
        <td>
          <button class='btn btn-danger btn-sm' onclick='eliminarProducto({$row['idProducto']})'>
            X
          </button>
        </td>
     <td>
          " . (empty($row['imagen'])
    ? "  <i class='fas fa-box'></i> "
    : "<img src='data:image/jpeg;base64," . base64_encode($row['imagen']) . "' width='50'>"
  ) . "
      </td>

        <td>{$row['nombre']}</td>
        <td>
            <input type='number' class='form-control cantidadInput' value='{$row['cantidad']}' 
            min='1' max='{$stockDisponible}' data-stock='{$stockDisponible}' 
            onchange='actualizarCantidad({$row['idProducto']}, this.value); validarCarrito();' 
            oninput='validarCarrito()'>

                   <small class='text-danger " . ($stockInsuficiente ? "" : "d-none") . "'>
                ⚠ Stock agotado
            </small>
        </td>
        <td>S/. " . number_format($row['precioTotal'], 2) . "</td>
        <td class='fw-bold text-success'>S/. " . number_format($subtotal, 2) . "</td>
    </tr>";
}

echo json_encode([
  "html" => $html,
  "total" => number_format($totalVenta, 2)
]);
