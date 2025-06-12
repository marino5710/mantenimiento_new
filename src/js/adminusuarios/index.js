import { Dropdown, Modal } from 'bootstrap';
import DataTable from 'datatables.net-bs5';
import { lenguaje } from './../lenguaje';
import { Toast, confirmacion, validarFormulario, ocultarLoader, mostrarLoader } from './../funciones';

ocultarLoader();


const formUsuarios = document.querySelector('#formUsuario')
const formReporte = document.querySelector('#formReporte')
const modalElement = document.querySelector('#modalNuevoUsuarios')
const modalBSUsuario = new Modal(modalElement)
const modalElementRepote = document.querySelector('#modalReporte')
const modalBSReporte = new Modal(modalElementRepote)
const spanLoader = document.getElementById('spanLoader')
const btnCrear = document.getElementById('btnCrear')
const spanLoaderModificar = document.getElementById('spanLoaderModificar')
const btnModificar = document.getElementById('btnModificar')
const modalTitleId = document.getElementById('modalTitleId')
const inputIdUsuario = document.getElementById('usuario_id')
const Inputidusuario = document.getElementById('idusuario'); 
const btnGenerarPass = document.getElementById('btnGenerarPass'); 
const spanLoaderGenerar = document.getElementById('spanLoaderGenerar'); 
spanLoader.style.display = 'none';
spanLoaderModificar.style.display = 'none';
spanLoaderGenerar.style.display = 'none';
btnModificar.style.display = 'none';
btnModificar.disabled = true


let i = 1;
let datatableUsuarios = new DataTable('#datatableUsuarios', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title : 'No.',
            width : '2%',
            render: (data, type, row, meta) => {
                return meta.row + 1; // El contador comienza desde 1
            }
        },
        {
            title : 'Correo',
            data: 'usuario_correo',
        },
        {
            title : 'Nombre',
            data: 'usuario_nombre',
        },
        {
            title : 'Apellido',
            data: 'usuario_apellido',
        },
        {
            title : 'Rol Asignado',
            data: 'rol_nombre',
        },

        {
            title : 'DPI',
            data: 'usuario_dpi',
        },
        {
            title : 'NIT',
            data: 'usuario_nit',
        },
        {
            title :'Acciones',
            data: 'usuario_id',
            width : '19%',
            searchable : false,
            render: (data, type, row, meta) => {
                let html = `
                <div class='text-center'>
                <button style='min-width: 31px; max-width: 32px; min-height: 31px; max-height: 32px' class="btn btn-secondary btn-sm rounded-circle reporte" title='Generar Contraseña' data-idusuario ='${data}'><i class='fas fa-file-pdf fa-xs'></i></button>
                <button style='min-width: 31px; max-width: 32px; min-height: 31px; max-height: 32px' data-bs-toggle='modal' data-bs-target='#modalNuevoUsuarios' class="btn btn-warning btn-sm rounded-circle editar" title='Modificar' data-codigo='${data}' data-nombre='${row['usuario_nombre']}' data-apellido='${row['usuario_apellido']}' data-correo='${row['usuario_correo']}' data-dpi='${row['usuario_dpi']}' data-nit='${row['usuario_nit']}' data-rol='${row['rol_id']}'><i class='fas fa-file-pen fa-xs'></i></button>`
                if(row.usuario_situacion == 1){
                    html += `<button style='min-width: 31px; max-width: 32px; min-height: 31px; max-height: 32px' class="btn btn-danger btn-sm rounded-circle eliminar" data-codigo='${data}' title='Desactivar'><i class='fas fa-times fa-xs'></i></button>`
                }else{
                    html += `<button style='min-width: 31px; max-width: 32px; min-height: 31px; max-height: 32px' class="btn btn-success btn-sm rounded-circle activar" data-codigo='${data}' title='Activar'><i class='fas fa-plus fa-xs'></i></button>`
                }
                html +=`</div>`

                return html;
            }
        },
    ]
});




const guardarApi = async e => {
    e.preventDefault();
    spanLoader.style.display = '';
    btnCrear.disabled = true;
    if(!validarFormulario(formUsuarios, ['usuario_id'] )){
        Toast.fire({
            icon : "warning",
            title : "Revise la información ingresada",
        })
        spanLoader.style.display = 'none';
        btnCrear.disabled = false;
        return
    }

    try {
        const url = `/API/adminusuarios/guardar`
        const headers = new Headers();
        const body = new FormData(formUsuarios);
        body.delete('usuario_id')
        headers.append('X-Requested-With','fetch');
        const config = {
            method : 'POST',
            body,
            headers,
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        //console.log(data);
        const {codigo, mensaje, detalle} = data;

        let icon = "";
        switch (codigo) {
            case 1:
                    icon = "success"
                    formUsuarios.reset();
                    modalBSUsuario.hide();
                    buscarApi();
                break;
            case 0:
                icon = "error"
                console.log(detalle);
                break;

        }

        Toast.fire({
            icon,
            title: mensaje,
        })
    } catch (error) {
        console.log(error);
    }
    spanLoader.style.display = 'none';
    btnCrear.disabled = false;
}




//// Función para buscar las roles y colocarlas en el select rol_id
let roles = [];


const buscarRoles = async () => {

    const url = `/API/adminusuarios/buscarRoles?`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);

        roles = data; 

        formUsuarios.rol_id.innerHTML = ''; 
        
        if (data.length === 1) {
            const rol = data[0];
            
            // Crear la opción con el dato recibido
            const option = document.createElement('option');
            option.value = rol.rol_id;
            option.textContent = rol.rol_nombre;
            option.classList.add('readonly'); // clase readonly
            
            // Añadir la opción al select
            formUsuarios.rol_id.appendChild(option);
        } else {
            // Agregar opción por defecto
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Seleccione una rol para asignarlo al usuario.';
            formUsuarios.rol_id.appendChild(defaultOption);

            // Iterar sobre los datos y agregar las opciones al select
            data.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.rol_id;
                option.textContent = rol.rol_nombre;
                formUsuarios.rol_id.appendChild(option);
            });
        }

    } catch (error) {
        console.log(error);
    }

};





const buscarApi = async () => {
    try {
        const url = `/API/adminusuarios/buscar`
        const headers = new Headers();
        headers.append('X-Requested-With','fetch');
        const config = {
            method : 'GET',
            headers,
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const {datos, mensaje, codigo, detalle} = data;
        console.log(data);
        datatableUsuarios.clear().draw();
        if(codigo == 1){
            datatableUsuarios.rows.add(datos).draw();
        }else{
            Toast.fire({
                icon : 'info',
                title: mensaje,
            })
        }

    } catch (error) {
        console.log(error);
    }
}
buscarApi();
buscarRoles();


const modificarApi = async e => {
    e.preventDefault();
    spanLoaderModificar.style.display = '';
    btnModificar.disabled = true;
    if(!validarFormulario(formUsuarios)){
        Toast.fire({
            icon : "warning",
            title : "Revise la información ingresada",
        })
        spanLoaderModificar.style.display = 'none';
        btnModificar.disabled = false;
        return
    }

    try {
        const url = `/API/adminusuarios/modificar`
        const headers = new Headers();
        const body = new FormData(formUsuarios);
        headers.append('X-Requested-With','fetch');
        const config = {
            method : 'POST',
            body,
            headers,
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);
        const {codigo, mensaje, detalle} = data;

        let icon = "";
        switch (codigo) {
            case 1:
                    icon = "success"
                    formUsuarios.reset();
                    modalBSUsuario.hide();
                    buscarApi();
                break;
            case 0:
                icon = "error"
                console.log(detalle);
                break;

        }

        Toast.fire({
            icon,
            title: mensaje,
        })
    } catch (error) {
        console.log(error);
    }
    spanLoaderModificar.style.display = 'none';
    btnModificar.disabled = false;
}


const asignarValores = (e) =>{
    const data = e.currentTarget.dataset;
    formUsuarios.usuario_id.value = data.codigo
    formUsuarios.usuario_nombre.value = data.nombre
    formUsuarios.usuario_apellido.value = data.apellido
    formUsuarios.usuario_correo.value = data.correo
    formUsuarios.usuario_dpi.value = data.dpi
    formUsuarios.usuario_nit.value = data.nit
    formUsuarios.rol_id.value = data.rol
    modalTitleId.textContent  = 'Modificar usuario'
    btnCrear.style.display = 'none'
    btnModificar.style.display = ''
    btnCrear.disabled = true
    btnModificar.disabled = false
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
}

const resetearModal = () => {
    modalTitleId.textContent  = 'Ingresar Usuario'
    btnCrear.style.display = ''
    btnModificar.style.display = 'none'
    btnCrear.disabled = false
    btnModificar.disabled = true
    spanLoader.style.display = 'none';
    spanLoaderModificar.style.display = 'none';
    formUsuarios.reset();
}

const verReporte = (e) => {
    formReporte.reset();
    const data = e.currentTarget.dataset;
    const idusuario = data.idusuario;
    Inputidusuario.value = idusuario;
    modalBSReporte.show();
}


const eliminarApi = async (e) => {
    const dataset = e.currentTarget.dataset;
    const id = dataset.codigo
    console.log(id);
    const confirm = await confirmacion('¿Esta seguro que desea desactivar este usuario?', 'warning', 'Si, desactivar');
    if (confirm) {
        try {
            const url = `/API/adminusuarios/eliminar`
            const headers = new Headers();
            const body = new FormData();
            body.append('usuario_id', id)
            body.append('usuario_situacion', 0)
            headers.append('X-Requested-With','fetch');
            const config = {
                method : 'POST',
                body,
                headers,
            }
    
            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data);
            const {codigo, mensaje, detalle} = data;
    
            let icon = "";
            switch (codigo) {
                case 1:
                        icon = "success"
                        formUsuarios.reset();
                        buscarApi();
                    break;
                case 0:
                    icon = "error"
                    console.log(detalle);
                    break;
    
            }
    
            Toast.fire({
                icon,
                title: mensaje,
            })
        } catch (error) {
            console.log(error);
        }
    }
}

const activarApi = async (e) => {
    const dataset = e.currentTarget.dataset;
    const id = dataset.codigo
    console.log(id);
    const confirm = await confirmacion('¿Esta seguro que desea activar este usuario?', 'warning', 'Si, activar');
    if (confirm) {
        try {
            const url = `/API/adminusuarios/eliminar`
            const headers = new Headers();
            const body = new FormData();
            body.append('usuario_id', id)
            body.append('usuario_situacion', 1)
            headers.append('X-Requested-With','fetch');
            const config = {
                method : 'POST',
                body,
                headers,
            }
    
            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data);
            const {codigo, mensaje, detalle} = data;
    
            let icon = "";
            switch (codigo) {
                case 1:
                        icon = "success"
                        formUsuarios.reset();
                        buscarApi();
                    break;
                case 0:
                    icon = "error"
                    console.log(detalle);
                    break;
    
            }
    
            Toast.fire({
                icon,
                title: mensaje,
            })
        } catch (error) {
            console.log(error);
        }
    }
}



const generarReporte = (e) => {
    e.preventDefault();
    spanLoaderGenerar.style.display = '';
    btnGenerarPass.disabled = true;
    const width = 800, height = 600;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    const idusuario = document.getElementById('idusuario').value;

    const options = `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes,status=yes`;
    const win = window.open(`/password/imprimir?usuario=${idusuario}`, 'Reporte Contraseña Confidencial' , options);
    win.focus();
    spanLoaderGenerar.style.display = 'none';
    btnGenerarPass.disabled = false;
}


formUsuarios.addEventListener('submit', guardarApi);
btnGenerarPass.addEventListener('click', generarReporte);

datatableUsuarios.on('click', '.editar', asignarValores );
datatableUsuarios.on('click', '.eliminar', eliminarApi );
datatableUsuarios.on('click', '.activar', activarApi );
datatableUsuarios.on('click', '.reporte', verReporte );

btnModificar.addEventListener('click', modificarApi);
modalElement.addEventListener('show.bs.modal', resetearModal);
