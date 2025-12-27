    <!-- Modal de editar productos -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Editar Producto</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="idProducto" id="edit-id">
                        <!--Imagen-->
                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-image"></i></span>
                                <input type="file"
                                    name="imagen"
                                    id="edit-imagen"
                                    class="form-control"
                                    accept="image/*">

                            </div>
                        </div>
                        <!--Nombre y documento-->
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-box-seam"></i></span>
                                    <input type="text" name="nombre" id="edit-nombre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Sku</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-card-text"></i></span>
                                    <input type="text" name="codigo" id="edit-codigo" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Stock</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-stack"></i></span>
                                    <input type="text" name="stock" id="edit-stock" class="form-control">

                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Precio</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"> <i class="bi bi-cash-coin"></i></span>
                                    <input type="number" step="any" min="0" name="precio" id="edit-precio" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- opciones de rubro + opciones de departamento -->
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Categoria</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"> <i class="fas fa-th-large"></i></span>
                                    <!--<input type="text" name="rubro" class="form-control" id="edit-rubro">-->
                                    <!--poner un select mostrando el rubro seleccionado y luego mostrar los rubros de la base de datos-->
                                    <select name="categoria" id="edit-categoria" class="form-select" required>
                                        <option value="">Seleccione categoría</option>
                                        <?php
                                        $resultadoCatego = $conexion->query("SELECT id_categorias, nombre, id_user FROM categorias where Eliminado = 0 AND id_user=" . intval($_SESSION['usId']) . " ");
                                        while ($cate = $resultadoCatego->fetch_assoc()) {
                                            echo '<option value="' . $cate['id_categorias'] . '">'
                                                . htmlspecialchars($cate['nombre']) .
                                                '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Marca</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"> <i class="fas fa-industry me-2"></i></span>
                                    <!--<input type="text" name="rubro" class="form-control" id="edit-rubro">-->
                                    <!--poner un select mostrando el rubro seleccionado y luego mostrar los rubros de la base de datos-->
                                    <select name="marca" id="edit-marca" class="form-select" required>
                                        <option value="">Seleccione marca</option>
                                        <?php
                                        $resultadoMarca = $conexion->query("SELECT id_marca, nombre, id_user FROM marcas where Eliminado = 0 AND id_user=" . intval($_SESSION['usId']) . " ");
                                        while ($marca = $resultadoMarca->fetch_assoc()) {
                                            echo '<option value="' . $marca['id_marca'] . '">'
                                                . htmlspecialchars($marca['nombre']) .
                                                '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Proveedor</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"> <i class="fas fa-truck"></i></span>
                                    <!--<input type="text" name="rubro" class="form-control" id="edit-rubro">-->
                                    <!--poner un select mostrando el rubro seleccionado y luego mostrar los rubros de la base de datos-->
                                    <select name="provedor" id="edit-provedor" class="form-select" required>
                                        <option value="">Seleccione proveedor</option>
                                        <?php
                                        $resultadoProv = $conexion->query("SELECT id_provedor, nombre, id_user FROM provedores where Eliminado = 0 AND id_user=" . intval($_SESSION['usId']) . " ");
                                        while ($prov = $resultadoProv->fetch_assoc()) {
                                            echo '<option value="' . $prov['id_provedor'] . '">'
                                                . htmlspecialchars($prov['nombre']) .
                                                '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--descripcion-->
                        <div class="mb-3">
                            <label class="form-label">Descripcion</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-geo"></i></span>
                                <input type="text" id="edit-descripcion" name="descripcion" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <!--<a type="submit" class="btn btn-primary" id="btnGuardarCambios">Guardar cambios</a>-->
                    </div>
                </form>
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipoAlerta; ?> mt-3">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>
                <div id="mensajeActualizacion" class="mt-2"></div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este producto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</a>
                </div>
            </div>
        </div>
    </div>