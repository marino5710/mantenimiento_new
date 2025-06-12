import { Modal } from 'bootstrap';
import DataTable from 'datatables.net-bs5';
import { lenguaje } from './../lenguaje';
import { Toast, confirmacion, validarFormulario, ocultarLoader, mostrarLoader } from './../funciones';
import Swal from 'sweetalert2';

ocultarLoader();

const modal = new Modal(document.getElementById('modalNuevo'));
const form = document.getElementById('formPrincipal');
const spanLoader = document.getElementById('spanLoader');
const spanLoaderModificar = document.getElementById('spanLoaderModificar');
const btnCrear = document.getElementById('btnCrear');
const btnModificar = document.getElementById('btnModificar');
const modalTitleId = document.getElementById('modalTitleId');

spanLoader.style.display = 'none';
spanLoaderModificar.style.display = 'none';
btnModificar.style.display = 'none';
btnModificar.disabled = true;

// ------------------------------ DATATABLE ------------------------------ //
const datatablePrincipal = new DataTable('#datatablePrincipal', {
    language: lenguaje,
    ajax: {
        url: '/API/pesos/buscar',
        dataSrc: json => {
            return json.codigo === 1 ? json.datos : [];
        }
    },
    columns: [
        { title: "#", render: (data, type, row, meta) => meta.row + 1 },
        { title: "Tipo de Joya", data: "tipojoya_nombre_corto" },
        { 
            title: "Tipo", 
            data: "peso_tipo",
            render: data => data === 'V' ? 'Venta' : 'Compra'
        },
        { 
            title: "Porcentaje (%)", 
            data: "peso_porcentaje", 
            render: data => parseFloat(data).toFixed(2)
        },
        {
            title: "Acciones",
            data: "pesos_id",
            render: (data, type, row) => `
                <button class="btn btn-sm btn-warning editar" 
                    data-id="${data}" 
                    data-tipojoya="${row.pesos_tipojoya_id}" 
                    data-tipo="${row.peso_tipo}" 
                    data-porcentaje="${row.peso_porcentaje}">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger eliminar" data-id="${data}">
                    <i class="fas fa-trash-alt"></i>
                </button>`
        }
    ]
});

// ------------------------------ CARGAR SELECT ------------------------------ //
const cargarTiposJoya = async () => {
    const select = form.pesos_tipojoya_id;
    select.innerHTML = `<option value="">Cargando...</option>`;

    try {
        const res = await fetch('/API/tiposjoya/buscar');
        const data = await res.json();
        select.innerHTML = `<option value="">Seleccione</option>`;
        data.datos.forEach(item => {
            const option = document.createElement('option');
            option.value = item.tipojoya_id;
            option.textContent = item.tipojoya_nombre_corto;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando tipos de joya', error);
    }
};
cargarTiposJoya();

// ------------------------------ GUARDAR ------------------------------ //
const guardar = async e => {
    e.preventDefault();
    spanLoader.style.display = '';
    btnCrear.disabled = true;

    if (!validarFormulario(form, ['pesos_id'])) {
        Toast.fire({ icon: 'warning', title: 'Complete los campos requeridos' });
        spanLoader.style.display = 'none';
        btnCrear.disabled = false;
        return;
    }

    const body = new FormData(form);
    const res = await fetch('/API/pesos/guardar', {
        method: 'POST',
        body,
        headers: { 'X-Requested-With': 'fetch' }
    });

    const data = await res.json();
    const icon = data.codigo === 1 ? 'success' : 'error';

    Toast.fire({ icon, title: data.mensaje });

    if (data.codigo === 1) {
        modal.hide();
        form.reset();
        datatablePrincipal.ajax.reload();
    }

    spanLoader.style.display = 'none';
    btnCrear.disabled = false;
};

// ------------------------------ MODIFICAR ------------------------------ //
const modificar = async e => {
    e.preventDefault();
    spanLoaderModificar.style.display = '';
    btnModificar.disabled = true;

    if (!validarFormulario(form)) {
        Toast.fire({ icon: 'warning', title: 'Complete los campos requeridos' });
        spanLoaderModificar.style.display = 'none';
        btnModificar.disabled = false;
        return;
    }

    const body = new FormData(form);
    const res = await fetch('/API/pesos/modificar', {
        method: 'POST',
        body,
        headers: { 'X-Requested-With': 'fetch' }
    });

    const data = await res.json();
    const icon = data.codigo === 1 ? 'success' : 'error';

    Toast.fire({ icon, title: data.mensaje });

    if (data.codigo === 1) {
        modal.hide();
        form.reset();
        datatablePrincipal.ajax.reload();
    }

    spanLoaderModificar.style.display = 'none';
    btnModificar.disabled = false;
};

// ------------------------------ ELIMINAR ------------------------------ //
const eliminar = async (e) => {
    const id = e.currentTarget.dataset.id;
    const confirm = await confirmacion("¿Desea eliminar este registro?", "warning", "Sí, eliminar");

    if (!confirm) return;

    const body = new FormData();
    body.append('pesos_id', id);

    const res = await fetch('/API/pesos/eliminar', {
        method: 'POST',
        body,
        headers: { 'X-Requested-With': 'fetch' }
    });

    const data = await res.json();
    const icon = data.codigo === 1 ? 'success' : 'error';

    Toast.fire({ icon, title: data.mensaje });

    if (data.codigo === 1) {
        datatablePrincipal.ajax.reload();
    }
};

// ------------------------------ RESETEAR MODAL ------------------------------ //
const resetearModal = () => {
    form.reset();
    form.pesos_id.value = '';
    btnCrear.style.display = '';
    btnModificar.style.display = 'none';
    btnCrear.disabled = false;
    btnModificar.disabled = true;
    modalTitleId.textContent = 'Asignar Porcentaje';
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
};

// ------------------------------ ASIGNAR VALORES ------------------------------ //
const asignarValores = (e) => {
    const d = e.currentTarget.dataset;
    form.pesos_id.value = d.id;
    form.pesos_tipojoya_id.value = d.tipojoya;
    form.peso_tipo.value = d.tipo;
    form.peso_porcentaje.value = parseFloat(d.porcentaje).toFixed(2);
    modalTitleId.textContent = 'Modificar Porcentaje';
    btnCrear.style.display = 'none';
    btnModificar.style.display = '';
    btnModificar.disabled = false;
    modal.show();
};

// ------------------------------ EVENTOS ------------------------------ //
form.addEventListener('submit', guardar);
btnModificar.addEventListener('click', modificar);
document.querySelector('[data-bs-target="#modalNuevo"]').addEventListener('click', resetearModal);


datatablePrincipal.on('click', '.editar', asignarValores);
datatablePrincipal.on('click', '.eliminar', eliminar);
