import { Dropdown, Modal, } from 'bootstrap';
import DataTable from 'datatables.net-bs5';
import { lenguaje } from './../lenguaje';
import { Toast, confirmacion, validarFormulario, ocultarLoader, mostrarLoader } from './../funciones';
import Swal from 'sweetalert2';

ocultarLoader();

const formPrincipal = document.querySelector('#formPrincipal')
const modalElement = document.querySelector('#modalNuevo')
const modalBSprincipal = new Modal(modalElement)
const spanLoader = document.getElementById('spanLoader')
const btnCrear = document.getElementById('btnCrear')
const spanLoaderModificar = document.getElementById('spanLoaderModificar')
const btnModificar = document.getElementById('btnModificar')
const modalTitleId = document.getElementById('modalTitleId')
const modalImagen = new Modal(document.getElementById('modalImagen'));
const formImagen = document.getElementById('formImagen');
const btnSubirImagen = document.getElementById('btnSubirImagen');
const inputImagen = document.getElementById('imgProducto');
spanLoader.style.display = 'none';
spanLoaderModificar.style.display = 'none';
btnModificar.style.display = 'none';
btnModificar.disabled = true

const modalElementImg = document.querySelector('#modalImgGrandeZoom')
const modalImgGrande = new Modal(modalElementImg);
const imgGrande = document.getElementById('imgGrande');

const modalMovimiento = new Modal(document.getElementById('modalMovimiento'));
const formMovimiento = document.getElementById('formMovimiento');
const btnMovimiento = document.getElementById('btnMovimiento');
const spinnerMovimiento = document.getElementById('spinnerMovimiento');

const modalVerMovimientos = new Modal(document.getElementById('modalVerMovimientos'));
const tablaMovimientos = document.getElementById('tablaMovimientosBody');


let datatablePrincipal = new DataTable('#datatablePrincipal', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Imágenes',
            data: null,
            render: (data, type, row) => {
                const img1 = row.producto_imagen ? `<img src="images/productos/${row.producto_imagen}" class="img-thumbnail me-1" style="width: 60px; height: 60px;">` : '';
                const img2 = row.producto_imagen2 ? `<img src="images/productos/${row.producto_imagen2}" class="img-thumbnail me-1" style="width: 60px; height: 60px;">` : '';
                const img3 = row.producto_imagen3 ? `<img src="images/productos/${row.producto_imagen3}" class="img-thumbnail" style="width: 60px; height: 60px;">` : '';
                const contenido = `${img1}${img2}${img3}`;
                return contenido || '<span class="text-muted">Sin imagen</span>';
            }
        },
        
        {
            title: 'Código',
            data: 'producto_codigo'
        },
        {
            title: 'Nombre',
            data: 'producto_nombre'
        },
        {
            title: 'Precio (Q)',
            data: 'producto_precio',
            render: function(data) {
                return parseFloat(data).toFixed(2); // asegura siempre 2 decimales
            }
        },
        {
            title: 'Precio Compra (Q) (Calc)',
            data: 'producto_precio_compra',
            render: function(data) {
                return parseFloat(data).toFixed(2);
            }
        },
        {
            title: 'Precio Venta (Q) (Calc)',
            data: 'producto_precio_venta',
            render: function(data) {
                return isNaN(parseFloat(data)) ? '0.00' : parseFloat(data).toFixed(2);
            }
        },
        {
            title: 'Peso en Grs.',
            data: 'producto_peso',
            render: function(data) {
                return isNaN(parseFloat(data)) ? '0.00' : parseFloat(data).toFixed(2);
            }
        },        
        {
            title: 'Descripción',
            data: 'producto_descripcion',
            render: function(data) {
                return data || '<span class="text-muted">Sin descripción</span>';
            }
        },

        {
            title: 'Categoría',
            data: 'categoria_nombre'
        },
        {
            title: 'Marca',
            data: 'marca_nombre'
        },
        {
            title: 'Tipo de Joya',
            data: 'tipojoya_nombre_corto'
        },
        {
            title: 'Stock',
            data: 'producto_stock',
            render: (data) => {
                let clase = 'text-success';
                if (parseInt(data) === 0) clase = 'text-danger fw-bold';
                else if (parseInt(data) > 0 && parseInt(data) < 5) clase = 'text-warning fw-bold';
                return `<span class="${clase}">${data}</span>`;
            }
        },
        {
            title: 'Acciones',
            data: 'producto_id',
            render: (data, type, row) => {
                return `
                    <div class='text-center'>
                        <button class="btn btn-warning btn-sm rounded-circle editar" data-bs-toggle='modal' data-bs-target='#modalNuevo'
                            data-id='${data}' data-codigo='${row.producto_codigo}' data-nombre='${row.producto_nombre}'
                            data-categoria='${row.categoria_id}' data-marca='${row.marca_id}' data-tipo='${row.tipojoya_id}'     data-precio='${row.producto_precio}' 
                            data-precio_compra='${row.producto_precio_compra}' 
                            data-descripcion='${row.producto_descripcion}'
                            data-peso='${row.producto_peso}' data-precioventa='${row.producto_precio_venta}'
                            title='Modificar'><i class='fas fa-edit'></i></button>
                        <button class="btn btn-info btn-sm rounded-circle imagen" title="Cambiar imagen" data-codigo='${data}'><i class='fas fa-image'></i></button>
                        <button class="btn btn-success btn-sm rounded-circle movimiento-ingreso" 
                            data-id='${data}' data-codigo='${row.producto_codigo}' data-nombre='${row.producto_nombre}' 
                            data-marca='${row.marca_nombre}' data-tipojoya='${row.tipojoya_nombre_corto}' 
                            title='Ingreso'><i class='fas fa-arrow-down'></i></button>
                        <button class="btn btn-danger btn-sm rounded-circle movimiento-egreso" 
                            data-id='${data}' data-codigo='${row.producto_codigo}' data-nombre='${row.producto_nombre}' 
                            data-marca='${row.marca_nombre}' data-tipojoya='${row.tipojoya_nombre_corto}'
                            title='Egreso'><i class='fas fa-arrow-up'></i></button>
                        <button class="btn btn-primary btn-sm rounded-circle ver-movimientos" 
                            data-id='${data}' data-nombre='${row.producto_nombre}' 
                            title='Ver movimientos'><i class='fas fa-clipboard-list'></i></button>
                        <button class="btn btn-dark btn-sm rounded-circle eliminar" data-codigo='${data}' title='Eliminar'>
                            <i class='fas fa-trash-alt'></i>
                          </button>
                          
                    </div>`;
            }
        }
    ]
});

// ----------------------------- Cargar Selects ----------------------------- //
const cargarSelect = async (url, selectId, nombreCampo, placeholder = 'Seleccione una opción') => {
    try {
        const response = await fetch(url);
        const data = await response.json();
        const select = formPrincipal[selectId];
        select.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = placeholder;
        select.appendChild(defaultOption);

        if (Array.isArray(data.datos)) {
            data.datos.forEach(item => {
                const option = document.createElement('option');
                option.value = item[`${selectId}`];
                option.textContent = item[nombreCampo];
                select.appendChild(option);
            });
        } else {
            console.warn('No se obtuvo una lista válida de datos para:', selectId);
        }

    } catch (error) {
        console.log('Error cargando select ' + selectId, error);
    }
};


const cargarCombos = () => {
    cargarSelect('/API/categorias/buscar', 'categoria_id', 'categoria_nombre');
    cargarSelect('/API/marcas/buscar', 'marca_id', 'marca_nombre');
};

cargarCombos();

// -------------------------- CRUD Funciones Básicas -------------------------- //

const buscarApi = async (filtros = {}) => {
    try {
        const params = new URLSearchParams(filtros).toString();
        const url = `/API/productos/buscar${params ? '?' + params : ''}`;
        const headers = new Headers({ 'X-Requested-With': 'fetch' });

        const respuesta = await fetch(url, { method: 'GET', headers });
        const data = await respuesta.json();

        datatablePrincipal.clear().draw();
        if (data.codigo == 1) {
            datatablePrincipal.rows.add(data.datos).draw();
        } else {
            Toast.fire({ icon: 'info', title: data.mensaje });
        }
    } catch (error) {
        console.log(error);
    }
}
buscarApi();

const guardarApi = async e => {
    e.preventDefault();
    spanLoader.style.display = '';
    btnCrear.disabled = true;
    if (!validarFormulario(formPrincipal, ['producto_id', 'producto_descripcion', 'producto_imagen2', 'producto_imagen3'])) {
        Toast.fire({ icon: "warning", title: "Revise la información ingresada" });
        spanLoader.style.display = 'none';
        btnCrear.disabled = false;
        return;
    }

    try {
        const url = `/API/productos/guardar`;
        const body = new FormData(formPrincipal);
        body.delete('producto_id');

        const respuesta = await fetch(url, {
            method: 'POST',
            body,
            headers: new Headers({ 'X-Requested-With': 'fetch' })
        });
        const data = await respuesta.json();

        let icon = data.codigo === 1 ? "success" : "error";
        if (data.codigo === 1) {
            formPrincipal.reset();
                        // Ocultar previews y limpiar src
            ['imagen', 'imagen2', 'imagen3'].forEach(num => {
                const preview = document.getElementById(`preview_${num}`);
                if (preview) {
                preview.classList.add('d-none');
                preview.src = '';
                }
            });
  
            modalBSprincipal.hide();
            buscarApi();
        }

        Toast.fire({ icon, title: data.mensaje });
    } catch (error) {
        console.log(error);
    }

    spanLoader.style.display = 'none';
    btnCrear.disabled = false;
}

const modificarApi = async e => {
    e.preventDefault();
    spanLoaderModificar.style.display = '';
    btnModificar.disabled = true;
    if (!validarFormulario(formPrincipal,  ['producto_imagen', 'producto_imagen2','producto_imagen3'])) {
        Toast.fire({ icon: "warning", title: "Revise la información ingresada" });
        spanLoaderModificar.style.display = 'none';
        btnModificar.disabled = false;
        return;
    }

    try {
        const url = `/API/productos/modificar`;
        const body = new FormData(formPrincipal);

        const respuesta = await fetch(url, {
            method: 'POST',
            body,
            headers: new Headers({ 'X-Requested-With': 'fetch' })
        });
        const data = await respuesta.json();

        let icon = data.codigo === 1 ? "success" : "error";
        if (data.codigo === 1) {
            formPrincipal.reset();
            modalBSprincipal.hide();
            buscarApi();
        }

        Toast.fire({ icon, title: data.mensaje });
    } catch (error) {
        console.log(error);
    }

    spanLoaderModificar.style.display = 'none';
    btnModificar.disabled = false;
}

const eliminarApi = async (e) => {
    const id = e.currentTarget.dataset.codigo;

    const confirm = await confirmacion(
        '¿Desea eliminar este producto? Solo se eliminará si no tiene stock disponible.',
        'warning',
        'Sí, eliminar'
    );

    if (!confirm) return;

    const body = new FormData();
    body.append('producto_id', id);

    try {
        const respuesta = await fetch(`/API/productos/eliminar`, {
            method: 'POST',
            body,
            headers: new Headers({ 'X-Requested-With': 'fetch' })
        });

        const data = await respuesta.json();

        if (data.codigo === 1) {
            Toast.fire({ icon: 'success', title: data.mensaje });
            buscarApi(); // recargar productos
        } else {
            // Usar sweetalert para errores relevantes como stock > 0
            Swal.fire({
                icon: 'error',
                title: 'No se puede eliminar',
                text: data.mensaje,
                confirmButtonText: 'Entendido'
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonText: 'OK'
        });
        console.error('Error en eliminarApi:', error);
    }
};

const asignarValores = (e) => {
    const data = e.currentTarget.dataset;
    formPrincipal.producto_id.value = data.id;
    formPrincipal.producto_codigo.value = data.codigo;
    formPrincipal.producto_nombre.value = data.nombre;
    formPrincipal.categoria_id.value = data.categoria;
    formPrincipal.marca_id.value = data.marca;
    formPrincipal.tipojoya_id.value = data.tipo;
    formPrincipal.producto_precio.value = parseFloat(data.precio).toFixed(2);
    formPrincipal.producto_precio_compra.value = parseFloat(data.precio_compra).toFixed(2);
    formPrincipal.producto_peso.value = parseFloat(data.peso).toFixed(2);
    formPrincipal.producto_precio_venta.value = parseFloat(data.precioventa).toFixed(2);
    formPrincipal.producto_descripcion.value = data.descripcion;
    modalTitleId.textContent = 'Modificar Producto';
    btnCrear.style.display = 'none';
    btnModificar.style.display = '';
    btnCrear.disabled = true;
    btnModificar.disabled = false;
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
     // Ocultar o desactivar el input de imagen al modificar

     if (inputImagen) {
         inputImagen.disabled = true;
         inputImagen.classList.add('d-none'); // Oculta visualmente si querés
     }
}

const resetearModal = () => {
    modalTitleId.textContent = 'Ingresar Producto';
    btnCrear.style.display = '';
    btnModificar.style.display = 'none';
    btnCrear.disabled = false;
    btnModificar.disabled = true;
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
    formPrincipal.reset();

    if (inputImagen) {
        inputImagen.disabled = false;
        inputImagen.classList.remove('d-none'); // Mostrar nuevamente
    }
}


datatablePrincipal.on('click', '.imagen', (e) => {
    const id = e.currentTarget.dataset.codigo;
    const producto = datatablePrincipal.row(e.currentTarget.closest('tr')).data();

    document.getElementById('producto_id_imagen').value = id;

    const imgPath = 'images/productos/';
    document.getElementById('img_actual_1').src = producto.producto_imagen ? `${imgPath}${producto.producto_imagen}` : 'images/sin-imagen.png';
    document.getElementById('img_actual_2').src = producto.producto_imagen2 ? `${imgPath}${producto.producto_imagen2}` : 'images/sin-imagen.png';
    document.getElementById('img_actual_3').src = producto.producto_imagen3 ? `${imgPath}${producto.producto_imagen3}` : 'images/sin-imagen.png';

    modalImagen.show();
});


formImagen.addEventListener('submit', async (e) => {
    e.preventDefault();
    btnSubirImagen.disabled = true;

    try {
        const formData = new FormData(formImagen);
        const respuesta = await fetch('/API/productos/imagen', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'fetch'
            }
        });

        const data = await respuesta.json();
        let icon = data.codigo === 1 ? "success" : "error";

        Toast.fire({
            icon,
            title: data.mensaje
        });

        if (data.codigo === 1) {
            formImagen.reset();
               // Limpiar previews de nuevas imágenes y mostrar las actuales
    ['1', '2', '3'].forEach(num => {
        const nueva = document.getElementById(`img_nueva_${num}`);
        const actual = document.getElementById(`img_actual_${num}`);
        if (nueva && actual) {
            nueva.src = '';
            nueva.classList.add('d-none');
            actual.classList.remove('d-none');
        }
    });
            modalImagen.hide();
            buscarApi();
        }
    } catch (error) {
        console.error('Error al subir imagen', error);
        Toast.fire({ icon: 'error', title: 'Error al subir imagen' });
    }

    btnSubirImagen.disabled = false;
});

datatablePrincipal.on('click', '.movimiento-ingreso', (e) => {
    const id = e.currentTarget.dataset.id;
    const codigo = e.currentTarget.dataset.codigo;
    const nombre = e.currentTarget.dataset.nombre;
    const marca = e.currentTarget.dataset.marca;
    const tipo = e.currentTarget.dataset.tipojoya;

    document.getElementById('mov_producto_id').value = id;
    document.getElementById('mov_tipo').value = 'I';
    document.getElementById('mov_codigo').value = codigo;
    document.getElementById('mov_nombre').value = nombre;
    document.getElementById('mov_marca').value = marca;
    document.getElementById('mov_tipojoya').value = tipo;

    document.getElementById('modalMovimientoLabel').textContent = 'Registrar Ingreso de Producto';
    modalMovimiento.show();
});

datatablePrincipal.on('click', '.movimiento-egreso', (e) => {
    const id = e.currentTarget.dataset.id;
    const codigo = e.currentTarget.dataset.codigo;
    const nombre = e.currentTarget.dataset.nombre;
    const marca = e.currentTarget.dataset.marca;
    const tipo = e.currentTarget.dataset.tipojoya;

    document.getElementById('mov_producto_id').value = id;
    document.getElementById('mov_tipo').value = 'E';
    document.getElementById('mov_codigo').value = codigo;
    document.getElementById('mov_nombre').value = nombre;
    document.getElementById('mov_marca').value = marca;
    document.getElementById('mov_tipojoya').value = tipo;

    document.getElementById('modalMovimientoLabel').textContent = 'Registrar Egreso de Producto';
    modalMovimiento.show();
});

btnMovimiento.addEventListener('click', async () => {
    spinnerMovimiento.classList.remove('d-none');
    btnMovimiento.disabled = true;

    const body = new FormData(formMovimiento);

    try {
        const response = await fetch('/API/movimientos/guardar', {
            method: 'POST',
            body,
            headers: {
                'X-Requested-With': 'fetch'
            }
        });

        const data = await response.json();
        const icon = data.codigo === 1 ? 'success' : 'error';

        Toast.fire({
            icon,
            title: data.mensaje
        });

        if (data.codigo === 1) {
            formMovimiento.reset();
            modalMovimiento.hide();
            buscarApi(); // recargar productos
        }
    } catch (error) {
        console.error('Error al registrar movimiento', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al registrar movimiento'
        });
    }

    spinnerMovimiento.classList.add('d-none');
    btnMovimiento.disabled = false;
});


datatablePrincipal.on('click', '.ver-movimientos', async (e) => {
    const producto_id = e.currentTarget.dataset.id;
    const nombre = e.currentTarget.dataset.nombre;
    document.getElementById('tituloMovimientos').textContent = `Movimientos de: ${nombre}`;

    try {
        const respuesta = await fetch(`/API/movimientos/buscar?producto_id=${producto_id}`);
        const data = await respuesta.json();

        if (data.codigo === 1) {
            tablaMovimientos.innerHTML = '';
            data.datos.forEach((mov, index) => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${mov.mov_tipo === 'I' ? 'Ingreso' : 'Egreso'}</td>
                    <td>${mov.mov_cantidad}</td>
                    <td>${mov.mov_fecha}</td>
                    <td>${mov.mov_descripcion || '-'}</td>
                `;
                tablaMovimientos.appendChild(fila);
            });
        } else {
            tablaMovimientos.innerHTML = `<tr><td colspan="5" class="text-center text-muted">${data.mensaje}</td></tr>`;
        }

        modalVerMovimientos.show();
    } catch (error) {
        console.error('Error al cargar movimientos', error);
        tablaMovimientos.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error al cargar movimientos</td></tr>`;
        modalVerMovimientos.show();
    }
});

function mostrarPreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
  
    input.addEventListener('change', () => {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          preview.src = e.target.result;
          preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      } else {
        preview.src = '';
        preview.classList.add('d-none');
      }
    });
  }
  
  // Activar para los tres campos
  mostrarPreview('producto_imagen', 'preview_imagen');
  mostrarPreview('producto_imagen2', 'preview_imagen2');
  mostrarPreview('producto_imagen3', 'preview_imagen3');


  function actualizarPreview(form, inputId, actualId, nuevaId) {
    const input = form.querySelector(`#${inputId}`);
    const imgActual = form.querySelector(`#${actualId}`);
    const imgNueva = form.querySelector(`#${nuevaId}`);
  
    input.addEventListener('change', () => {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          imgNueva.src = e.target.result;
          imgNueva.classList.remove('d-none');
          imgActual.classList.add('d-none');
        };
        reader.readAsDataURL(file);
      } else {
        imgNueva.src = '';
        imgNueva.classList.add('d-none');
        imgActual.classList.remove('d-none');
      }
    });
  }
  
  // Apuntar todo al modal sin romper el formulario principal

  actualizarPreview(formImagen, 'producto_imagen', 'img_actual_1', 'img_nueva_1');
  actualizarPreview(formImagen, 'producto_imagen2', 'img_actual_2', 'img_nueva_2');
  actualizarPreview(formImagen, 'producto_imagen3', 'img_actual_3', 'img_nueva_3');
  
  
  
  const calcularPreciosPorPesoYTipo = async () => {
    const peso = parseFloat(formPrincipal.producto_peso.value) || 0;
    const tipojoya_id = formPrincipal.tipojoya_id.value;

    if (!tipojoya_id || peso <= 0) return;

    try {
        const res = await fetch(`/API/pesos/porcentaje?tipojoya_id=${tipojoya_id}`);
        const data = await res.json();

        if (data.codigo === 1) {
            const compra = peso * data.porcentajes.C;
            const venta = peso * data.porcentajes.V;

            formPrincipal.producto_precio_compra.value = compra.toFixed(2);
            formPrincipal.producto_precio_venta.value = venta.toFixed(2);
            formPrincipal.producto_precio.value = venta.toFixed(2); // editable
        } else {
            Toast.fire({ icon: 'warning', title: 'No hay porcentajes configurados para esta joya' });
        }
    } catch (error) {
        console.error('Error al calcular precios por peso y tipo:', error);
        Toast.fire({ icon: 'error', title: 'Error al calcular precios' });
    }
};

// Evento al cambiar el peso
// Evento al cambiar el peso
formPrincipal.producto_peso.addEventListener('input', () => {
    const valor = formPrincipal.producto_peso.value;

    // Si el campo está vacío o sólo tiene el punto decimal
    if (valor === '' || valor === '.' || valor.endsWith('.')) {
        formPrincipal.producto_precio_compra.value = '';
        formPrincipal.producto_precio_venta.value = '';
        formPrincipal.producto_precio.value = '';
        return;
    }

    calcularPreciosPorPesoYTipo();
});

//  Evento al cambiar el tipo de joya
formPrincipal.tipojoya_id.addEventListener('change', calcularPreciosPorPesoYTipo);


datatablePrincipal.on('click', 'img.img-thumbnail', function (e) {
    const imgSrc = e.target.getAttribute('src');
    if (imgSrc) {
        imgGrande.src = imgSrc;
        modalImgGrande.show();
    }
});

// Mostrar "ver imagen" en hover (opcional)
document.addEventListener('mouseover', function (e) {
    if (e.target.tagName === 'IMG' && e.target.classList.contains('img-thumbnail')) {
        e.target.style.cursor = 'zoom-in';
        e.target.title = 'Ver imagen en grande';
    }
});


async function cargarFiltros() {
    const [categorias, tipos, marcas] = await Promise.all([
      fetch('/API/categorias/buscar').then(res => res.json()),
      fetch('/API/tiposjoya/buscar').then(res => res.json()),
      fetch('/API/marcas/buscar').then(res => res.json()),
    ]);
  
    llenarSelect('filtro_categoria', categorias.datos, 'categoria_id', 'categoria_nombre');
    llenarSelect('filtro_tipojoya', tipos.datos, 'tipojoya_id', 'tipojoya_nombre_corto');
    llenarSelect('filtro_marca', marcas.datos, 'marca_id', 'marca_nombre');
  }
  
  
  function llenarSelect(id, datos, valueField, textField) {
    const select = document.getElementById(id);
    datos.forEach(d => {
      const option = document.createElement('option');
      option.value = d[valueField];
      option.textContent = d[textField];
      select.appendChild(option);
    });
  }
  
  document.getElementById('btnFiltrar').addEventListener('click', () => {
    const filtros = {
      categoria_id: document.getElementById('filtro_categoria').value,
      tipojoya_id: document.getElementById('filtro_tipojoya').value,
      marca_id: document.getElementById('filtro_marca').value
    };
    buscarApi(filtros);
  });
  
cargarFiltros()
formPrincipal.addEventListener('submit', guardarApi);
datatablePrincipal.on('click', '.editar', asignarValores);
datatablePrincipal.on('click', '.eliminar', eliminarApi);
btnModificar.addEventListener('click', modificarApi);
modalElement.addEventListener('show.bs.modal', resetearModal);

