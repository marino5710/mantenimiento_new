import Swal from "sweetalert2";
import { validarFormulario, Toast } from './../funciones';

const formLogin = document.getElementById('loginForm');
const loginButton = document.getElementById('btnLogin');
const spinner = document.getElementById('spinner');

let intentos = parseInt(localStorage.getItem('loginIntentos')) || 0;

// Función para verificar bloqueo temporal
const verificarBloqueo = () => {
    const bloqueoTiempo = localStorage.getItem('bloqueoTiempo');
    if (bloqueoTiempo && Date.now() < parseInt(bloqueoTiempo)) {
        loginButton.disabled = true;
        Toast.fire({
            icon: 'error',
            title: 'Acceso bloqueado temporalmente. Intenta en unos minutos.'
        });
        return true;
    } else {
        localStorage.removeItem('bloqueado');
        localStorage.removeItem('bloqueoTiempo');
        localStorage.removeItem('loginIntentos');
        loginButton.disabled = false;
        return false;
    }
};

// Función para manejar el login
const login = async (e) => {
    e.preventDefault();

    if (verificarBloqueo()) return;

    loginButton.disabled = true;
    spinner.classList.remove('d-none');

    if (!validarFormulario(formLogin)) {
        Toast.fire({
            icon: 'info',
            title: 'Debe llenar todos los campos'
        });
        loginButton.disabled = false;
        spinner.classList.add('d-none');
        return;
    }

    try {
        const url = '/API/login/buscar';
        const formData = new FormData(formLogin);
        const headers = new Headers();
        headers.append("X-Requested-With", "fetch");

        const config = {
            method: 'POST',
            headers,
            body: formData
        };

        const response = await fetch(url, config);
        const data = await response.json();
        const { mensaje, codigo, redireccion } = data;

        if (codigo === 1) {
            localStorage.clear();
            window.location.href = redireccion;
        } else if (codigo === 2) {
            Toast.fire({ icon: 'error', title: mensaje });
        } else {
            intentos++;
            localStorage.setItem('loginIntentos', intentos);

            if (intentos === 1) {
                Toast.fire({ icon: 'info', title: 'Usuario o contraseña incorrectos. Intenta nuevamente.' });
            } else if (intentos >= 3) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Demasiados intentos. Intenta nuevamente en 1 minuto.'
                });
                localStorage.setItem('bloqueado', true);
                localStorage.setItem('bloqueoTiempo', Date.now() + 60000); // 1 minuto
                loginButton.disabled = true;
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Toast.fire({ icon: 'error', title: 'Hubo un error con la solicitud, por favor intenta de nuevo.' });
    }

    spinner.classList.add('d-none');
    if (!verificarBloqueo()) loginButton.disabled = false;
};

formLogin.addEventListener('submit', login);

document.addEventListener('DOMContentLoaded', function () {
    verificarBloqueo();

    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
});
