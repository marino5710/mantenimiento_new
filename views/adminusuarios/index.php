<h1>Ingreso de usuarios</h1>
<div class="row justify-content-center">
    <div class="col table-responsive">
        <table class="table table-bordered table-hover w-100" id="datatableUsuarios">
            <thead class='text-center'></thead>
            <tbody class='text-center'></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalNuevoUsuarios" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Ingresar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="formUsuario" enctype="multipart/form-data">
                <input type="hidden" name="usuario_id" id="usuario_id">
                <div class="row mb-3">
                    <div class="col">
                        <label for="usuario_nombre">Ingrese el nombre del usuario</label>
                        <input type="text" name="usuario_nombre" id="usuario_nombre" class="form-control" placeholder="Ingrese nombres">
                    </div>
                    <div class="col">
                        <label for="usuario_apellido">Ingrese el apellido del usuario</label>
                        <input type="text" name="usuario_apellido" id="usuario_apellido" class="form-control" placeholder="Ingrese apellidos">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="usuario_correo">Ingrese el correo del usuario</label>
                        <input type="email" name="usuario_correo" id="usuario_correo" class="form-control" placeholder="ejemplo@gmail.com">
                    </div>
                    <div class="col">
                        <label for="usuario_correo">Ingrese el DPI</label>
                        <input type="text" name="usuario_dpi" id="usuario_dpi" class="form-control" placeholder="Ingrese un DPI válido">
                    </div>
                    <div class="col">
                        <label for="usuario_correo">Ingrese el NIT</label>
                        <input type="text" name="usuario_nit" id="usuario_nit" class="form-control" placeholder="Ingrese un nit válido">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="rol">Seleccione el rol a asignar</label>
                            <select name="rol_id" id="rol_id" class="form-control mb-2">
                                <option value="">Seleccione el rol a asignar (!Importante¡)...</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button id="btnModificar" class="btn btn-warning">Modificar <span
                        class="spinner-grow spinner-grow-sm ms-2" id="spanLoaderModificar" role="status"
                        aria-hidden="true"></span></button>
                <button type="submit" form="formUsuario" id="btnCrear" class="btn btn-primary">Crear <span
                        class="spinner-grow spinner-grow-sm ms-2" id="spanLoader" role="status"
                        aria-hidden="true"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReporte" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleIdReporte" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleIdReporte">
                    Generar Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="formReporte">
                <div class="form-text">¿Desea Generar una contraseña para este usuario?</div>
                <div class="row mb-3">
                        <input type="hidden" class="form-control" name="idusuario" id="idusuario">
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button form="formReporte" type="button" id="btnGenerarPass"class="btn btn-primary">Sí, Generar<span
                        class="spinner-grow spinner-grow-sm ms-2" id="spanLoaderGenerar" role="status"
                        aria-hidden="true"></span></button>
            </div>
        </div>
    </div>
</div>
<button data-bs-toggle="modal" data-bs-target="#modalNuevoUsuarios" class="btn btn-primary btn-lg rounded-circle  float-button"><i class="fas fa-plus"></i></button>



<script src="<?= asset('build/js/adminusuarios/index.js') ?>"></script>