
<h1>Ingreso de Marcas</h1>

<div class="row justify-content-center">
    <div class="col table-responsive">
        <table class="table table-bordered table-hover w-100" id="datatablePrincipal">
            <thead class='text-center'></thead>
            <tbody class='text-center'></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNuevo" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Ingresar Marca
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="modal-body" id="formPrincipal" enctype="multipart/form-data">
                <input type="hidden" name="marca_id" id="marca_id">

                <div class="row mb-3">
                    <div class="col">
                        <label for="marca_nombre">Nombre de la marca</label>
                        <input type="text" name="marca_nombre" id="marca_nombre" class="form-control" placeholder="Ej. Pandora, Tous">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="marca_descripcion">Descripción</label>
                        <textarea name="marca_descripcion" id="marca_descripcion" class="form-control" placeholder="Descripción de la marca" rows="3"></textarea>
                    </div>
                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btnModificar" class="btn btn-warning">Modificar
                    <span class="spinner-grow spinner-grow-sm ms-2" id="spanLoaderModificar" role="status" aria-hidden="true"></span>
                </button>
                <button type="submit" form="formPrincipal" id="btnCrear" class="btn btn-primary">Crear
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

<!-- Script -->
<script src="<?= asset('build/js/marcas/index.js') ?>"></script>