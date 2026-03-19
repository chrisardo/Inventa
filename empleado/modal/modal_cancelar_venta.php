<!-- Modal Confirmar Cancelación -->
<div class="modal fade" id="modalCancelarVenta" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalCancelarLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar cancelación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <p class="mb-3">
                    ¿Estás seguro de cancelar esta venta?
                </p>
                <small class="text-muted">
                    Esta acción devolverá el stock y marcará la venta como cancelada.
                </small>
            </div>

            <div class="modal-footer justify-content-center">

                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    No, volver
                </button>

                <!-- Formulario real que cancela -->
                <form action="controladores/cancelar_venta.php" method="POST">
                    <input type="hidden" name="id_ticket" value="<?= $venta['id_ticket_ventas'] ?>">
                    <button type="submit" class="btn btn-danger">
                        Sí, cancelar venta
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>