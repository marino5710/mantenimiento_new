<h1>Ingreso de Productos</h1>

<div class="row mb-4">
  <div class="col-md-3">
    <label for="filtro_categoria">Categoría</label>
    <select id="filtro_categoria" class="form-select">
      <option value="">-- Todas --</option>
    </select>
  </div>
  <div class="col-md-3">
    <label for="filtro_marca">Marca</label>
    <select id="filtro_marca" class="form-select">
      <option value="">-- Todas --</option>
    </select>
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button id="btnFiltrar" class="btn btn-primary w-100">
      <i class="fas fa-filter me-2"></i>Filtrar
    </button>
  </div>
</div>

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
                <h5 class="modal-title" id="modalTitleId">Ingresar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="modal-body" id="formPrincipal" enctype="multipart/form-data">
              <input type="hidden" name="producto_id" id="producto_id">

              <div class="row mb-3">
                  <div class="col">
                      <label for="producto_codigo">Código del producto</label>
                      <input type="text" name="producto_codigo" id="producto_codigo" class="form-control" placeholder="Ej. JY123456">
                  </div>
                  <div class="col">
                      <label for="producto_nombre">Nombre del producto</label>
                      <input type="text" name="producto_nombre" id="producto_nombre" class="form-control" placeholder="Ej. Cadena de plata 925">
                  </div>
              </div>

              <div class="row mb-3">
                  <div class="col">
                      <label for="categoria_id">Categoría</label>
                      <select name="categoria_id" id="categoria_id" class="form-control"></select>
                  </div>
                  <div class="col">
                      <label for="marca_id">Marca</label>
                      <select name="marca_id" id="marca_id" class="form-control"></select>
                  </div>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="producto_precio" class="form-label text-truncate d-block" title="Precio del producto">Precio del producto</label>
                  <input type="number" name="producto_precio" id="producto_precio" class="form-control" step="0.01" min="0" value="0.00">
                </div>
                <div class="col">
                  <label for="producto_precio_compra" class="form-label text-truncate d-block" title="Precio en que se compró (calculado)">Precio compra (calc.)</label>
                  <input type="number" name="producto_precio_compra" id="producto_precio_compra" class="form-control" step="0.01" min="0" value="0.00" readonly>
                </div>
                <div class="col">
                  <label for="producto_precio_venta" class="form-label text-truncate d-block" title="Precio en que se vende (calculado)">Precio venta (calc.)</label>
                  <input type="number" name="producto_precio_venta" id="producto_precio_venta" class="form-control" step="0.01" min="0" value="0.00" readonly>
                </div>
                
              </div>


              <div class="row mb-3">
                  <div class="col">
                    <label for="producto_descripcion">Descripción</label>
                    <textarea name="producto_descripcion" id="producto_descripcion" class="form-control" rows="3" placeholder="Ej: Anillo de plata con circonias"></textarea>
                  </div>
              </div>

              
              <div class="row mb-3" id="imgProducto">
                <div class="col">
                  <label for="producto_imagen">Imagen principal</label>
                  <input type="file" name="producto_imagen" id="producto_imagen" accept="image/*" class="form-control">
                  <img id="preview_imagen" src="" alt="Vista previa" class="img-fluid mt-2 d-none" style="max-height: 120px;">
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

<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog" aria-labelledby="modalImagenLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalImagenLabel">Actualizar Imágenes del Producto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form id="formImagen" enctype="multipart/form-data">
        <input type="hidden" id="producto_id_imagen" name="producto_id">

        <div class="modal-body">
          <div class="row text-center">
          <div class="mb-3">
              <label class="form-label fw-bold">Imagen principal</label>
              <div class="border p-2 rounded bg-light text-center">
                <img id="img_actual_1" src="images/productos/sin-imagen.png" class="img-thumbnail mb-2" style="max-height: 140px;" alt="Imagen principal actual">
                <img id="img_nueva_1" class="img-thumbnail mb-2 d-none" style="max-height: 140px;" alt="Nueva imagen seleccionada">
                <input type="file" name="producto_imagen" id="producto_imagen" class="form-control" accept="image/*">
              </div>
            </div>

        </div>
        </div>


        <div class="modal-footer bg-light">
          <button type="submit" id="btnSubirImagen" class="btn btn-primary w-100">
            <i class="fas fa-upload me-2"></i>Actualizar Imágenes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modalMovimiento" tabindex="-1" aria-labelledby="modalMovimientoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalMovimientoLabel">Movimiento de Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form class="modal-body" id="formMovimiento">
        <!-- Campos ocultos -->
        <input type="hidden" name="producto_id" id="mov_producto_id">
        <input type="hidden" name="mov_tipo" id="mov_tipo">

        <div class="mb-2">
          <label for="mov_codigo" class="form-label">Código del Producto</label>
          <input type="text" class="form-control" id="mov_codigo" readonly>
        </div>

        <div class="mb-2">
          <label for="mov_nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="mov_nombre" readonly>
        </div>

        <div class="mb-2">
          <label for="mov_marca" class="form-label">Marca</label>
          <input type="text" class="form-control" id="mov_marca" readonly>
        </div>
        <div class="mb-2">
          <label for="mov_cantidad" class="form-label">Cantidad</label>
          <input type="number" class="form-control" id="mov_cantidad" name="mov_cantidad" min="1" required>
        </div>

        <div class="mb-2">
          <label for="mov_descripcion" class="form-label">Descripción (opcional)</label>
          <input type="text" class="form-control" id="mov_descripcion" name="mov_descripcion" maxlength="255">
        </div>
      </form>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button"  id="btnMovimiento" class="btn btn-primary">
          Guardar Movimiento
          <span class="spinner-grow spinner-grow-sm ms-2 d-none" id="spinnerMovimiento" role="status" aria-hidden="true"></span>
        </button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalVerMovimientos" tabindex="-1" aria-labelledby="modalVerMovimientosLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-dark">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="tituloMovimientos">Movimientos del producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-primary text-center">
              <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Descripción</th>
              </tr>
            </thead>
            <tbody id="tablaMovimientosBody" class="text-center">
              <!-- Aquí se insertan dinámicamente los movimientos -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>



<!-- Modal Imagen Grande -->
<div class="modal fade" id="modalImgGrandeZoom" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Vista de Imagen</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imgGrande" src="" class="img-fluid rounded shadow" style="max-height: 600px;" alt="Vista de imagen grande">
      </div>
    </div>
  </div>
</div>


<!-- Botón flotante -->
<button data-bs-toggle="modal" data-bs-target="#modalNuevo" class="btn btn-primary btn-lg rounded-circle float-button">
    <i class="fas fa-plus"></i>
</button>

<!-- Script -->
<script src="<?= asset('build/js/productos/index.js') ?>"></script>