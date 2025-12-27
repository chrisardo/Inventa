<!--Esta parte es controladores/modal_lista_productos_ventas.php-->
<div class="modal fade" id="modalProductos" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-success">

                <h5 class="modal-title text-white"><i class="fas fa-cart-plus"></i> Agregar producto al carrito</h5>
                <button type="button" class="btn-close btn-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="col py-4">
                    <div class="card">
                        <input id="inputBuscar" class="form-control" type="search" placeholder="Buscar producto por nombre o código">
                    </div>
                </div>
                <table class="table table-striped table-hover text-center table-sm w-100">
                    <thead class="table-success">
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Marca</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProductos">

                        <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                            <tr>
                                <td>
                                    <?php if ($row['imagen']): ?>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen']); ?>"
                                            width="60">
                                    <?php else: ?>
                                        <i class="fas fa-box"></i> <?php endif; ?>
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
                        <?php } ?>

                    </tbody>
                </table>
                <!--Mostrar el total de registro del la tabla, anterior, siguiente-->
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">

                    <!-- Total de registros -->
                    <div>
                        <span class="fw-bold">Total de registros:</span>
                        <span id="totalRegistros">0</span>
                    </div>

                    <!-- Indicador de página -->
                    <div class="fw-bold text-success">
                        Página <span id="paginaActual">1</span> de <span id="totalPaginas">1</span>
                    </div>

                    <!-- Botones de paginación -->
                    <div>
                        <button id="btnAnterior" class="btn btn-outline-success btn-sm me-2">
                            ⬅ Anterior
                        </button>

                        <button id="btnSiguiente" class="btn btn-outline-success btn-sm">
                            Siguiente ➡
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>