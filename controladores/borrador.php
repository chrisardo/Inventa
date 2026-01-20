if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar') {

header('Content-Type: application/json');

// ===== CAMPOS OBLIGATORIOS =====
$requeridos = ['nombre', 'codigo', 'precio', 'costo', 'stock', 'categoria', 'marca', 'provedor'];

foreach ($requeridos as $campo) {
if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
echo json_encode([
"tipo" => "danger",
"mensaje" => "Todos los campos son obligatorios."
]);
exit();
}
}

// ===== STOCK ENTERO =====
if (!ctype_digit($_POST['stock'])) {
echo json_encode([
"tipo" => "danger",
"mensaje" => "El stock debe ser un número entero sin decimales."
]);
exit();
}
$categoria = isset($_POST['categoria']) ? (int)$_POST['categoria'] : 0;

if ($categoria <= 0) {
  echo json_encode([ "tipo"=> "danger",
  "mensaje" => "Debe seleccionar una categoría válida."
  ]);
  exit();
  }

  // ===== VALIDAR IMAGEN =====
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

  $max = 1.8 * 1024 * 1024;
  $mime = mime_content_type($_FILES['imagen']['tmp_name']);

  if ($_FILES['imagen']['size'] > $max) {
  echo json_encode([
  "tipo" => "danger",
  "mensaje" => "La imagen supera los 1.8 MB."
  ]);
  exit();
  }

  if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/png'])) {
  echo json_encode([
  "tipo" => "danger",
  "mensaje" => "Solo se permiten imágenes JPG o PNG."
  ]);
  exit();
  }

  $imagenData = file_get_contents($_FILES['imagen']['tmp_name']);
  }

  // ===== LIMPIAR =====
  $idProducto = (int)$_POST['idProducto'];
  $nombre = $conexion->real_escape_string($_POST['nombre']);
  $codigo = $conexion->real_escape_string($_POST['codigo']);
  $descripcion = $conexion->real_escape_string($_POST['descripcion'] ?? '');
  $precio = floatval($_POST['precio']);
  $costo = floatval($_POST['costo']);
  $categoria = (int)$_POST['categoria'];
  $proveedor = (int)$_POST['provedor'];
  $marca = (int)$_POST['marca'];
  $stock = (int)$_POST['stock'];
  $usId = $_SESSION['usId'];

  // ===== UPDATE =====
  if (isset($imagenData)) {

  $stmt = $conexion->prepare("
  UPDATE producto SET
  nombre=?, descripcion=?, precio=?, codigo=?, stock=?, imagen=?,
  id_categorias=?, id_provedor=?, id_marca=?, costo_compra=?
  WHERE idProducto=? AND id_user=?
  ");

  $null = NULL;

  $stmt->bind_param(
  "ssdsiibiiii",
  $nombre,
  $descripcion,
  $precio,
  $codigo,
  $stock,
  $null,
  $categoria,
  $proveedor,
  $marca,
  $costo,
  $idProducto,
  $usId
  );

  $stmt->send_long_data(5, $imagenData);
  $ok = $stmt->execute();
  } else {

  $ok = $conexion->query("
  UPDATE producto SET
  nombre='$nombre',
  descripcion='$descripcion',
  precio=$precio,
  codigo='$codigo',
  stock=$stock,
  id_categorias=$categoria,
  id_provedor=$proveedor,
  id_marca=$marca,
  costo_compra=$costo
  WHERE idProducto=$idProducto AND id_user=$usId
  ");
  }

  echo json_encode([
  "tipo" => $ok ? "success" : "danger",
  "mensaje" => $ok
  ? "Producto actualizado correctamente."
  : "Error al actualizar: " . $stmt->error
  ]);
  exit();
  }