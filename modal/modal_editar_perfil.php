<!-- Modal Editar Perfil -->
<div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="controladores/actualizar_perfil.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="usId" value="<?= $usuario['id_user'] ?>">
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Foto de Perfil</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/png, image/jpeg">
                        <small class="text-muted">
                            Máximo 1.7 MB · PNG o JPG
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="nombreEmpresa" class="form-label">Nombre Empresa</label>
                        <input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa" value="<?= htmlspecialchars($usuario['nombreEmpresa']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="celular" class="form-label">Celular</label>
                        <input type="number" min="0" class="form-control" id="celular" name="celular" value="<?= htmlspecialchars($usuario['celular']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($usuario['direccion']) ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
            <!--mostrar div mensaje-->
            <div id="alertPerfil" class="alert d-none" role="alert"></div>
        </div>
    </div>
</div>