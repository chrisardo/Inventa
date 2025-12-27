    <!-- Modal de editar proveedorea -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Editar proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id_proveedor" id="edit-id">
                        <!--Imagen-->
                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-image"></i></span>
                                <input type="file" name="imagen" id="edit-imagen" class="form-control" accept="image/png, image/jpeg">
                            </div>
                            <small class="text-muted">
                                Máximo 1.5 MB · PNG o JPG
                            </small>
                        </div>
                        <!--Nombre y documento-->
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" id="edit-nombre" class="form-control" value="<?= htmlspecialchars($_SESSION['formEditar']['nombre'] ?? '') ?>" required>

                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Documento</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white"><i class="bi bi-card-text"></i></span>
                                    <input type="text" name="dni_o_ruc" id="edit-dni" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="fas fa-mobile-alt"></i>
                                    </span>
                                    <input type="text" name="celular" id="edit-celular" class="form-control" required>

                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="edit-email" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <!-- opciones de rubro + opciones de departamento -->
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Departamento</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white"><i class="bi bi-building"></i></span>
                                    <!--<input type="text" name="departamento" id="edit-departamento" class="form-control">-->
                                    <!--poner un select mostrando el departamento seleccionado y luego mostrar los departamentos de la base de datos-->
                                    <select name="departamento" id="edit-departamento" class="form-select" required>
                                        <?php
                                        $resultadoDepartamentos = $conexion->query("SELECT id_departamento, nombre, id_user FROM departamento WHERE id_user = " . intval($_SESSION['usId']) . " and Eliminado = 0");
                                        while ($departamento = $resultadoDepartamentos->fetch_assoc()) {
                                            echo '<option value="' . $departamento['id_departamento'] . '">' . htmlspecialchars($departamento['nombre']) . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Provincia</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" id="edit-provincia" name="provincia" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <!--distrito-->
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Distrito</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white"><i class="bi bi-geo-alt-fill"></i></span>
                                    <input type="text" id="edit-distrito" name="distrito" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <!--direccion-->
                                <label class="form-label">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-geo"></i></span>
                                    <input type="text" id="edit-direccion" name="direccion" class="form-control">
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-<?= $tipoAlerta ?> alert-dismissible fade show" role="alert">
                                <?= $mensaje ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <!--<a type="submit" class="btn btn-primary" id="btnGuardarCambios">Guardar cambios</a>-->
                    </div>
                </form>
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
                    ¿Estás seguro de que deseas eliminar este proveedor?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</a>
                </div>
            </div>
        </div>
    </div>