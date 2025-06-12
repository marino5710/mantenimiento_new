<style>
    :root {
        --text-color: #ffffff;
        --gold: #d4af37;
        --navy-glass: rgba(0, 33, 71, 0.5);
        --rose-gradient-start: #f9dce1;
        --rose-gradient-end: #f0b6c3;
    }

    body {
        margin: 0;
        height: 100vh;
        background: linear-gradient(to bottom right, var(--rose-gradient-start), var(--rose-gradient-end));
        overflow: hidden;
        position: relative;
        font-family: 'Segoe UI', sans-serif;
    }

    .star {
        position: absolute;
        width: 10px;
        height: 10px;
        background: var(--gold);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--gold);
        animation: fall 5s linear infinite;
        opacity: 0.8;
    }

    @keyframes fall {
        0% {
            transform: translateY(-20px);
            opacity: 1;
        }
        100% {
            transform: translateY(110vh);
            opacity: 0;
        }
    }

    .login-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: var(--navy-glass);
        padding: 2rem;
        border-radius: 15px;
        backdrop-filter: blur(15px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 400px;
        text-align: center;
        z-index: 10;
    }

    .login-header h1 {
        color: var(--gold);
        text-shadow: 1px 1px 3px #000;
    }

    .login-header p {
        color: var(--text-color);
        opacity: 0.8;
    }

    .form-group label {
        color: var(--text-color);
        display: block;
        margin-bottom: 0.3rem;
    }

    .form-group input {
        background-color: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: var(--text-color);
        padding: 0.7rem;
        border-radius: 5px;
        width: 100%;
    }

    .form-group input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .input-group .btn {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
    }

    .login-button {
        background-color: var(--gold);
        color: #000;
        border: none;
        padding: 0.75rem;
        border-radius: 5px;
        margin-top: 1rem;
        font-weight: bold;
        width: 100%;
        transition: all 0.3s ease;
    }

    .login-button:hover {
        background-color: #c59e30;
    }

    /* Fondo animado de estrellas */
    #stars-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
        pointer-events: none;
    }
    .g-recaptcha {
    transform: scale(0.88);
    transform-origin: 0 0;
    margin: 0.5rem 0 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    padding: 0.5rem;
    backdrop-filter: blur(5px);
}

</style>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<header style="text-align: center; padding: 1rem; color: var(--gold); font-size: 1.5rem; font-weight: bold;">
    Bienvenida a Destellos de Glamour ✨
</header>
<div id="stars-container"></div>


<div class="overlay"></div>
<div class="login-container">
    <div class="login-header">
        <h1>Destellos de Glamour</h1>
        <p>Sistema de Control Interno</p>
    </div>
    <form class="login-form" id="loginForm">
        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required placeholder="Ingrese su usuario">
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-group">
                <input type="password" id="password" name="password" required placeholder="Ingrese su contraseña"
                    class="form-control">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="g-recaptcha" data-sitekey="6LdfY2kqAAAAANzOwyWEspFqK5b5afNIkhLFfzAu" data-size="normal"
            data-callback="verificar" data-expired-callback="expirado" data-error-callback="error"></div>
        <button type="submit" id="btnLogin" class="login-button"><span
                class="spinner-border spinner-border-sm me-2 d-none" id="spinner"></span>Iniciar Sesión</button>
    </form>
</div>
<footer style="position: absolute; bottom: 0; width: 100%; text-align: center; padding: 1rem 0; background: linear-gradient(to right, #f7c0cb, #f1a7bd); color: white; font-size: 0.8rem; z-index: 5;">
    © <?= date('Y') ?> Destellos de Glamour · Todos los derechos reservados
</footer>

<script>
    const starsContainer = document.getElementById('stars-container');

    for (let i = 0; i < 50; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        star.style.left = `${Math.random() * 100}vw`;
        star.style.animationDuration = `${Math.random() * 5 + 3}s`;
        star.style.animationDelay = `${Math.random() * 5}s`;
        starsContainer.appendChild(star);
    }
</script>

<script src="<?= asset('build/js/login/index.js') ?>"></script>