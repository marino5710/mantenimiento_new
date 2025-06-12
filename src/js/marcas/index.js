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
            data: 'marca_nombre'
        },
        {
            title: 'Descripción',
            data: 'marca_descripcion'
        },
        {
            title: 'Acciones',
            data: 'marca_id',
            width: '10%',
            searchable: false,
            render: (data, type, row) => {
                let html = `
                <div class='text-center'>
                    <button class="btn btn-warning btn-sm rounded-circle editar" data-bs-toggle='modal' data-bs-target='#modalNuevo' title='Modificar'
                        data-codigo='${data}' data-nombre='${row['marca_nombre']}' data-descripcion='${row['marca_descripcion'] || ''}'>
                        <i class='fas fa-file-pen fa-xs'></i>
                    </button>`;
                if (row.marca_situacion == 1) {
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
        const url = `/API/marcas/buscar`;
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
    if (!validarFormulario(formPrincipal, ['marca_id'])) {
        Toast.fire({ icon: "warning", title: "Revise la información ingresada" });
        spanLoader.style.display = 'none';
        btnCrear.disabled = false;
        return;
    }

    try {
        const url = `/API/marcas/guardar`;
        const body = new FormData(formPrincipal);
        body.delete('marca_id');

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
        const url = `/API/marcas/modificar`;
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
    const confirm = await confirmacion('¿Desea desactivar esta marca?', 'warning', 'Sí, desactivar');
    if (confirm) {
        const body = new FormData();
        body.append('marca_id', id);
        body.append('marca_situacion', 0);

        const respuesta = await fetch(`/API/marcas/eliminar`, {
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
    const confirm = await confirmacion('¿Desea activar esta marca?', 'warning', 'Sí, activar');
    if (confirm) {
        const body = new FormData();
        body.append('marca_id', id);
        body.append('marca_situacion', 1);

        const respuesta = await fetch(`/API/marcas/eliminar`, {
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
    formPrincipal.marca_id.value = data.codigo;
    formPrincipal.marca_nombre.value = data.nombre;
    formPrincipal.marca_descripcion.value = data.descripcion;
    modalTitleId.textContent = 'Modificar Marca';
    btnCrear.style.display = 'none';
    btnModificar.style.display = '';
    btnCrear.disabled = true;
    btnModificar.disabled = false;
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
}

const resetearModal = () => {
    modalTitleId.textContent = 'Ingresar Marca';
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