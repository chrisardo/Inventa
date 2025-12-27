<!--Esta parte es modal/modal_registrar_venta.php-->
<div class="modal fade" id="modalVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Registrar Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cliente</label>
                        <select class="form-select" id="idCliente">
                            <option value="">-- Seleccione --</option>
                            <?php
                            include "./controladores/conexion.php";
                            $clientes = mysqli_query($conexion, "SELECT idCliente, nombre FROM clientes where id_user= " . $_SESSION['usId']);
                            while ($c = mysqli_fetch_assoc($clientes)) {
                                echo "<option value='{$c['idCliente']}'>{$c['nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Forma de Pago</label>
                        <select class="form-select" id="formaPago">
                            <option value="">-- Seleccione --</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Yape">Yape</option>
                            <option value="Plin">Plin</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total de la Venta (S/.)</label>
                    <h1 class="text-success">S/. <span id="totalModal">0.00</span></h1>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pago del Cliente (S/.)</label>
                    <input type="number" class="form-control" id="pago" oninput="calcularVuelto()" autocomplete="off">
                </div>

                <div class="mb-4 text-center">
                    <label class="form-label fw-bold">Vuelto del cliente</label>
                    <h1 class="text-primary">S/. <span id="vuelto">0.00</span></h1>
                </div>

                <div id="mensajePago" class="alert alert-danger mt-2 d-none"></div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="btnConfirmarVenta" disabled>Confirmar Venta</button>
            </div>
        </div>
    </div>
</div>