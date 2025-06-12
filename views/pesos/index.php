<h1>Configuración de Porcentajes de Peso</h1>

<div class="row justify-content-center">
    <div class="col table-responsive">
        <table class="table table-bordered table-hover w-100" id="datatablePrincipal">
            <thead class="text-center"></thead>
            <tbody class="text-center"></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNuevo" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTitleId">Asignar Porcentaje</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form class="modal-body" id="formPrincipal">
                <input type="hidden" name="pesos_id" id="pesos_id">

                <div class="mb-3">
                    <label for="pesos_tipojoya_id" class="form-label">Tipo de Joya</label>
                    <select name="pesos_tipojoya_id" id="pesos_tipojoya_id" class="form-select" required></select>
                </div>

                <div class="mb-3">
                    <label for="peso_porcentaje" class="form-label">Porcentaje (%)</label>
                    <input type="number" step="0.01" min="0" name="peso_porcentaje" id="peso_porcentaje" class="form-control" placeholder="Ej: 35.00" required>
                </div>

                <div class="mb-3">
                    <label for="peso_tipo" class="form-label">Tipo de Porcentaje</label>
                    <select name="peso_tipo" id="peso_tipo" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="C">Compra</option>
                        <option value="V">Venta</option>
                    </select>
                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btnModificar" class="btn btn-warning">
                    Modificar
                    <span class="spinner-grow spinner-grow-sm ms-2" id="spanLoaderModificar" role="status" aria-hidden="true"></span>
                </button>
                <button type="submit" form="formPrincipal" id="btnCrear" class="btn btn-primary">
                    Crear
                    <span class="spinner-grow spinner-grow-sm ms-2" id="spanLoader" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Botón flotante -->
<button data-bs-toggle="modal" data-bs-target="#modalNuevo" class="btn btn-primary btn-lg rounded-circle float-button">
    <i class="fas fa-plus"></i>
</button>

<script src="<?= asset('build/js/pesos/index.js') ?>"></script>
