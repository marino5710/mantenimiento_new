import { Dropdown, Modal, } from 'bootstrap';
import DataTable from 'datatables.net-bs5';
import { lenguaje } from './../lenguaje';
import { Toast, confirmacion, validarFormulario, ocultarLoader, mostrarLoader } from './../funciones';
import Swal from 'sweetalert2';

ocultarLoader();

const formPrincipal = document.querySelector('#formPrincipal')
const spanLoader = document.getElementById('spanLoader')



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
    cargarSelect('/API/tiposjoya/buscar', 'tipojoya_id', 'tipojoya_nombre_corto');
};

cargarCombos();

// -------------------------- CRUD Funciones Básicas -------------------------- //




  
  
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


