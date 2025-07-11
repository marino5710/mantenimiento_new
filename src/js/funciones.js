import Swal from 'sweetalert2';
export const validarFormulario = (formulario, excepciones = [] ) => {
    const elements = formulario.querySelectorAll("input, select, textarea");
    let validarFormulario = []
    elements.forEach( element => {
        if(!element.value.trim() && ! excepciones.includes(element.id) ){
            element.classList.add('is-invalid');
          
            validarFormulario.push(false)
        }else{
            element.classList.remove('is-invalid');
        }
    });

    let noenviar = validarFormulario.includes(false);

    return !noenviar;
}

export const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})


export const confirmacion = async (text = '¿Esta seguro que desea borrar este registro?', icon = 'warning', buttonText = 'Si, eliminar') => {
    const alerta = Swal.fire({
        title : 'Confirmación',
        icon,
        text,
        showCancelButton : true,
        confirmButtonColor : '#3085d6',
        cancelButtonColor : '#d33',
        cancelButtonText : 'Cancelar',
        confirmButtonText: buttonText
    })
    const resultado = (await alerta).isConfirmed
    return resultado;
}





export const ocultarLoader = () => {
    loader.style.visibility = 'hidden';
    loader.style.opacity = 0;
  }
export const mostrarLoader = () => {
loader.style.visibility = 'visible';
loader.style.opacity = 1;
}