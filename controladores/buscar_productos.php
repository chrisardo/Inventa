<?php
//Esta parte es controladores/buscar_productos.php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];
$buscar = $_POST['buscar'] ?? '';
$pagina = $_POST['pagina'] ?? 1;

$limite = 5;
$desde = ($pagina - 1) * $limite;

/* TOTAL DE REGISTROS */
$sqlTotal = "SELECT COUNT(*) AS total 
             FROM producto p
             INNER JOIN marcas c ON p.id_marca= c.id_marca
             WHERE p.id_user = '$usId' AND p.Eliminado = 0
             AND (
                p.nombre LIKE '%$buscar%' 
                OR p.codigo LIKE '%$buscar%'
                OR c.nombre LIKE '%$buscar%'
                OR p.nombre LIKE '%$buscar%'
             )";

$resTotal = mysqli_query($conexion, $sqlTotal);
$total = mysqli_fetch_assoc($resTotal)['total'];

/* CONSULTA PAGINADA */
$sql = "SELECT p.*, c.nombre AS nombre_categoria
        FROM producto p
        INNER JOIN marcas c ON p.id_marca = c.id_marca
        WHERE p.id_user = '$usId' AND p.Eliminado = 0
        AND (
            p.nombre LIKE '%$buscar%' 
            OR p.codigo LIKE '%$buscar%'
            OR c.nombre LIKE '%$buscar%'
            OR p.nombre LIKE '%$buscar%'
        )
        LIMIT $desde, $limite";

$resultado = mysqli_query($conexion, $sql);

/* TOTAL DE paginas */
$totalPaginas = ceil($total / $limite);

/* RESPUESTA */
$response = [
    "total" => $total,
    "totalPaginas" => $totalPaginas,
    "html" => ""
];

while ($row = mysqli_fetch_assoc($resultado)) {
    ob_start(); ?>
    <tr>
        <td>
            <?php if (base64_encode($row['imagen'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen']); ?>" width="60">

            <?php else: ?>
                <i class="fas fa-box"></i>
            <?php endif; ?>
        </td>
        <td><?php echo $row['nombre']; ?></td>
       
        <td>
            <?php if ($row['stock'] <= 0): ?>
                <span class="badge bg-danger">Sin stock</span>
            <?php elseif ($row['stock'] <= 5): ?>
                <span class="badge bg-warning text-dark"><?= $row['stock'] ?></span>
            <?php else: ?>
                <span class="badge bg-success"><?= $row['stock'] ?></span>
            <?php endif; ?>
        </td>

        </td>
        <td>S/.<?php echo number_format($row['precio'], 2); ?></td>
        <td><?php echo $row['nombre_categoria']; ?></td>
        <td>
            <button class="btn btn-success btn-sm"
                <?= ($row['stock'] <= 0) ? 'disabled' : '' ?>
                onclick="agregarAlCarrito(<?= $row['idProducto'] ?>)">
                <i class="fas fa-cart-plus"></i>
            </button>

        </td>
    </tr>
<?php
    $response['html'] .= ob_get_clean();
}

echo json_encode($response);
