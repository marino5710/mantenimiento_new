import { Dropdown, Modal } from 'bootstrap';
import DataTable from 'datatables.net-bs5';
import { lenguaje } from './../lenguaje';
import { Toast, confirmacion, validarFormulario, ocultarLoader, mostrarLoader } from './../funciones';

ocultarLoader();

const formPrincipal = document.querySelector('#formPrincipal')
const modalElement = document.querySelector('#modalNuevo')
const modalBSprincipal = new Modal(modalElement)
const spanLoader = document.getElementById('spanLoader')
const btnCrear = document.getElementById('btnCrear')
const spanLoaderModificar = document.getElementById('spanLoaderModificar')
const btnModificar = document.getElementById('btnModificar')
const modalTitleId = document.getElementById('modalTitleId')
spanLoader.style.display = 'none';
spanLoaderModificar.style.display = 'none';
btnModificar.style.display = 'none';
btnModificar.disabled = true

let datatablePrincipal = new DataTable('#datatablePrincipal', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            width: '2%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Nombre',
            data: 'tipojoya_nombre_corto'
        },
        {
            title: 'Descripción',
            data: 'tipojoya_descripcion'
        },
        {
            title: 'Acciones',
            data: 'tipojoya_id',
            width: '10%',
            searchable: false,
            render: (data, type, row) => {
                let html = `
                <div class='text-center'>
                    <button class="btn btn-warning btn-sm rounded-circle editar" data-bs-toggle='modal' data-bs-target='#modalNuevo' title='Modificar'
                        data-codigo='${data}' data-nombre='${row['tipojoya_nombre_corto']}' data-descripcion='${row['tipojoya_descripcion'] || ''}'>
                        <i class='fas fa-file-pen fa-xs'></i>
                    </button>`;
                if (row.tipojoya_situacion == 1) {
                    html += `<button class="btn btn-danger btn-sm rounded-circle eliminar" data-codigo='${data}' title='Desactivar'>
                        <i class='fas fa-times fa-xs'></i></button>`;
                } else {
                    html += `<button class="btn btn-success btn-sm rounded-circle activar" data-codigo='${data}' title='Activar'>
                        <i class='fas fa-plus fa-xs'></i></button>`;
                }
                return html + "</div>";
            }
        },
    ]
});

const buscarApi = async () => {
    try {
        const url = `/API/tiposjoya/buscar`;
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
    if (!validarFormulario(formPrincipal, ['tipojoya_id'])) {
        Toast.fire({ icon: "warning", title: "Revise la información ingresada" });
        spanLoader.style.display = 'none';
        btnCrear.disabled = false;
        return;
    }

    try {
        const url = `/API/tiposjoya/guardar`;
        const body = new FormData(formPrincipal);
        body.delete('tipojoya_id');

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

    spanLoader.style.display = 'none';
    btnCrear.disabled = false;
}

const modificarApi = async e => {
    e.preventDefault();
    spanLoaderModificar.style.display = '';
    btnModificar.disabled = true;
    if (!validarFormulario(formPrincipal)) {
        Toast.fire({ icon: "warning", title: "Revise la información ingresada" });
        spanLoaderModificar.style.display = 'none';
        btnModificar.disabled = false;
        return;
    }

    try {
        const url = `/API/tiposjoya/modificar`;
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
    const confirm = await confirmacion('¿Desea desactivar este tipo de joya?', 'warning', 'Sí, desactivar');
    if (confirm) {
        const body = new FormData();
        body.append('tipojoya_id', id);
        body.append('tipojoya_estado', 0);

        const respuesta = await fetch(`/API/tiposjoya/eliminar`, {
            method: 'POST',
            body,
            headers: new Headers({ 'X-Requested-With': 'fetch' })
        });
        const data = await respuesta.json();
        Toast.fire({ icon: data.codigo ? "success" : "error", title: data.mensaje });
        buscarApi();
    }
}

const activarApi = async (e) => {
    const id = e.currentTarget.dataset.codigo;
    const confirm = await confirmacion('¿Desea activar este tipo de joya?', 'warning', 'Sí, activar');
    if (confirm) {
        const body = new FormData();
        body.append('tipojoya_id', id);
        body.append('tipojoya_estado', 1);

        const respuesta = await fetch(`/API/tiposjoya/eliminar`, {
            method: 'POST',
            body,
            headers: new Headers({ 'X-Requested-With': 'fetch' })
        });
        const data = await respuesta.json();
        Toast.fire({ icon: data.codigo ? "success" : "error", title: data.mensaje });
        buscarApi();
    }
}

const asignarValores = (e) => {
    const data = e.currentTarget.dataset;
    formPrincipal.tipojoya_id.value = data.codigo;
    formPrincipal.tipojoya_nombre_corto.value = data.nombre;
    formPrincipal.tipojoya_descripcion.value = data.descripcion;
    modalTitleId.textContent = 'Modificar Tipo de Joya';
    btnCrear.style.display = 'none';
    btnModificar.style.display = '';
    btnCrear.disabled = true;
    btnModificar.disabled = false;
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
}

const resetearModal = () => {
    modalTitleId.textContent = 'Ingresar Tipo de Joya';
    btnCrear.style.display = '';
    btnModificar.style.display = 'none';
    btnCrear.disabled = false;
    btnModificar.disabled = true;
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
    formPrincipal.reset();
}

formPrincipal.addEventListener('submit', guardarApi);
datatablePrincipal.on('click', '.editar', asignarValores);
datatablePrincipal.on('click', '.eliminar', eliminarApi);
datatablePrincipal.on('click', '.activar', activarApi);
btnModificar.addEventListener('click', modificarApi);
modalElement.addEventListener('show.bs.modal', resetearModal);