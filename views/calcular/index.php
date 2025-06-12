<h1>Calcular precios de venta y compra conforme al peso en gramos y tipos de joya</h1>



<div class="row">
                <h5 class="modal-title" id="modalTitleId">Calcular precios</h5>
        

            <form class="modal-body" id="formPrincipal" enctype="multipart/form-data">
              <input type="hidden" name="producto_id" id="producto_id">

             
              <div class="row mb-3">
                  <div class="col">
                      <label for="tipojoya_id">Tipo de joya</label>
                      <select name="tipojoya_id" id="tipojoya_id" class="form-control"></select>
                  </div>
                  <small class="text-muted">Los precios se calculan automáticamente según el tipo de joya y peso.</small>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="producto_peso" class="form-label text-truncate d-block" title="Peso en gramos del producto">Peso en grs. del producto</label>
                  <input type="number" name="producto_peso" id="producto_peso" class="form-control" step="0.01" min="0" value="0.00">
                </div>
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


          </form>
</div>




<!-- Botón flotante -->
<button data-bs-toggle="modal" data-bs-target="#modalNuevo" class="btn btn-primary btn-lg rounded-circle float-button">
    <i class="fas fa-plus"></i>
</button>

<!-- Script -->
<script src="<?= asset('build/js/calcular/index.js') ?>"></script>