

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<header style="text-align: center; padding: 1rem; color: var(--gold); font-size: 1.5rem; font-weight: bold;">
    Bienvenido
</header>
<div id="stars-container"></div>


<div class="overlay"></div>
<div class="login-container">
    <div class="login-header">
        <h1>Login</h1>
        <p>Login</p>
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
    © <?= date('Y') ?> · Todos los derechos reservados
</footer>


<script src="<?= asset('build/js/login/index.js') ?>"></script>