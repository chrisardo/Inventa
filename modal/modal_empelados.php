    <?php
    if (!empty($mensaje)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
            modal.show();
            const alert = document.getElementById('alertaProveedor');
            alert.classList.remove('d-none');
            alert.classList.add('alert-danger');
            alert.innerHTML = " . json_encode($mensaje) . ";
        });
    </script>";
    }
    ?>
    <!-- Modal de editar proveedorea -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Editar empelado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id_empleado" id="edit-id">
                        <div id="alertaProveedor" class="alert d-none mt-2"></div>
                        <!-- Imagen -->
                        <div class="mb-2">
                            <div class="card border-0 shadow-sm">
                                <!-- Input -->
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="bi bi-image"></i>
                                    </span>
                                    <input
                                        type="file"
                                        name="imagen"
                                        id="edit-imagen"
                                        class="form-control"
                                        accept="image/png, image/jpeg">
                                </div>

                                <div class="form-text">
                                    Formatos permitidos: JPG, PNG · Tamaño máximo: 1.8 MB
                                </div>
                                <div class="card-body p-2">
                                    <!-- Vista previa -->
                                    <div id="previewImagen" class="mt-0 d-none">
                                        <div class="row align-items-center g-3">

                                            <!-- Imagen -->
                                            <div class="col-auto">
                                                <div class="border rounded p-2 bg-light">
                                                    <img
                                                        id="previewImg"
                                                        class="img-fluid rounded"
                                                        style="width: 70px; height: 60px; object-fit: cover;">
                                                </div>
                                            </div>

                                            <!-- Detalles -->
                                            <div class="col">
                                                <ul class="list-group list-group-flush small">
                                                    <!--<li class="list-group-item px-0">
                                                        <i class="bi bi-file-earmark-text text-success me-2"></i>
                                                        <strong>Nombre:</strong>
                                                        <span id="imgNombre"></span>
                                                    </li>-->
                                                    <li class="list-group-item px-0">
                                                        <i class="bi bi-aspect-ratio text-info me-2"></i>
                                                        <strong>Tipo:</strong>
                                                        <span id="imgTipo"></span>
                                                    </li>
                                                    <li class="list-group-item px-0">
                                                        <i class="bi bi-hdd text-warning me-2"></i>
                                                        <strong>Tamaño:</strong>
                                                        <span id="imgSize"></span>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--Nombre y documento-->
                        <div class="row g-2 mb-2">
                            <div class="col">
                                <label class="form-label">Nombres</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" id="edit-nombre" class="form-control" value="<?= htmlspecialchars($_SESSION['formEditar']['nombre'] ?? '') ?>" required>

                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Apellidos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-person"></i></span>
                                    <input type="text" name="apellido" id="edit-apellido" class="form-control" value="<?= htmlspecialchars($_SESSION['formEditar']['apellido'] ?? '') ?>" required>

                                </div>
                            </div>
                            
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col">
                                <label class="form-label">DNI</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white"><i class="bi bi-card-text"></i></span>
                                    <input type="text" name="dni_o_ruc" id="edit-dni" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="fas fa-mobile-alt"></i>
                                    </span>
                                    <input type="text" name="celular" id="edit-celular" class="form-control">

                                </div>
                            </div>

                        </div>
                        <!-- opciones de rubro + opciones de departamento -->
                        <div class="row g-2 mb-2">
                            <div class="col">
                                <label class="form-label">Departamento</label>
                                <div class="input-group">
                                    <!--<span class="input-group-text bg-info text-white"><i class="bi bi-building"></i></span>-->
                                    <!--<input type="text" name="departamento" id="edit-departamento" class="form-control">-->
                                    <!--poner un select mostrando el departamento seleccionado y luego mostrar los departamentos de la base de datos-->
                                    <select name="departamento" id="edit-departamento" class="form-select">
                                        <option value="">Sin departamento</option>
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
                                    <input type="text" id="edit-provincia" name="provincia" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Distrito</label>
                                <div class="input-group">
                                    <input type="text" id="edit-distrito" name="distrito" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!--distrito-->
                        <div class="row g-2 mb-2">

                            <div class="col">
                                <!--direccion-->
                                <label class="form-label">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-geo"></i></span>
                                    <input type="text" id="edit-direccion" name="direccion" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Estado</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="bi bi-toggle-on"></i>
                                    </span>
                                    <select name="estado" id="edit-estado" class="form-select" required>
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--email + contraseña -->
                        <div class="row g-2 mb-2">
                            <!--Email-->
                            <div class="col">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="edit-email" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <label for="contrasena" class="form-label">Nueva Contraseña:</label>

                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="bi bi-lock"></i>
                                    </span>

                                    <input
                                        type="password"
                                        class="form-control"
                                        id="edit-contrasena"
                                        name="contrasena"
                                        placeholder="Nueva contraseña"
                                        >

                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        👁️
                                    </button>
                                </div>
                            </div>
                            <!--Poner indicaciones que la contraseña debe ssr mayor a 8 caracteres y debe contener por lo menos: 1 letra mayuscula, numeros, 1 letra minuscula y 1 caracter especial-->
                        </div>
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
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('edit-contrasena');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>