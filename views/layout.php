<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="<?= asset('build/js/app.js') ?>"></script>
    <link rel="shortcut icon" href="<?= asset('images/destellos.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Destellos de Glamour</title>

    <style>
        body {
            background: linear-gradient(to right, #ffffff, #f7d9dc);
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* Loader */
        #loader {
            position: fixed;
            z-index: 5000;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.85);
        }

        /* Navbar */
        .navbar {
            background-color: #002147 !important;
        }

        .navbar-brand,
        .nav-link,
        .dropdown-item {
            color: #f7d9dc !important;
            font-weight: 500;
        }

        .btn-logout {
            background-color: #d4af37;
            border-color: #d4af37;
            color: #fff;
        }

        .btn-logout:hover {
            background-color: #c59e30;
            border-color: #c59e30;
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='white' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }

        /* Sidebar */
        .sidebar {
            background: #ffe6ea;
            min-height: 100vh;
            padding: 1rem;
        }

        .sidebar a {
            color: #002147;
            font-weight: 500;
            display: block;
            padding: 0.5rem 0;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #d4af37;
            color: #000;
            border-radius: 5px;
        }

        .submenu {
            display: none;
            padding-left: 1rem;
        }

        .sidebar .has-submenu.active .submenu {
            display: block;
        }

        .content {
            padding: 2rem;
        }

        h1,
        h2,
        h3 {
            color: #002147;
            font-weight: bold;
            text-shadow: 1px 1px 2px #00000030;
        }

        .footer-custom {
            background: linear-gradient(to right, #f7a6b1, #fcd2d7);
            color: #002147;
            font-weight: bold;
            padding: 10px 0;
            font-size: 0.8rem;
        }

        .modal-header,
        .modal-header.bg-dark {
            background-color: #002147 !important;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                top: 56px;
                /* altura navbar */
                height: 100%;
                width: 250px;
                background: #ffe6ea;
                transition: left 0.3s ease;
                z-index: 1050;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>

    <div id="loader" class="d-flex flex-column justify-content-center align-items-center">
        <div class="spinner-grow text-warning" role="status"></div>
        <p class="text-dark h5 fw-bold">Cargando</p>
    </div>

    <!-- Navbar superior -->
    <nav class="navbar navbar-expand-lg navbar-dark px-4 shadow-sm">
        <a class="navbar-brand fw-bold" href="/">
            <img src="<?= asset('images/destellos.png') ?>" width="35px" alt="logo" />
            Destellos de Glamour
        </a>
        <button class="btn d-md-none text-white me-3" id="toggleSidebar">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </button>

        <div class="ms-auto">
            <a href="/logout" class="btn btn-logout">
                <i class="bi bi-box-arrow-left me-1"></i> CERRAR SESIÓN
            </a>
        </div>
    </nav>

    <div class="row g-0">
        <!-- Sidebar lateral -->
        <div class="col-12 col-md-2 sidebar">
            <a href="/"><i class="bi bi-house-door-fill me-2"></i>Inicio</a>

            <a href="/adminusuarios"><i class="bi bi-person-gear me-2"></i>Usuarios</a>

            <div class="has-submenu" id="menuMantenimientos">
                <a href="#" onclick="toggleSubmenu('menuMantenimientos')">
                    <i class="bi bi-boxes me-2"></i>Mantenimientos
                </a>
                <div class="submenu">
                    <a href="/categorias"><i class="bi bi-tags-fill me-2"></i>Categorías</a>
                    <a href="/marcas"><i class="bi bi-award-fill me-2"></i>Marcas</a>
                    <a href="/tiposjoya"><i class="bi bi-gem me-2"></i>Tipos de Joya</a>
                    <a href="/pesos"><i class="bi bi-sliders2-vertical me-2"></i>Configuración de Porcentajes de Peso</a>
                    <a href="/calcular"><i class="bi bi-calculator me-2""></i>Calcular precios</a>

                </div>
            </div>

            <a href="/productos"><i class="bi bi-bag-fill me-2"></i>Productos</a>
        </div>

        <!-- Contenido -->
        <div class="col-12 col-md-10 content">
            <?php echo $contenido; ?>
        </div>
    </div>

    <div class="container-fluid text-center footer-custom mt-4">
        Destellos de Glamour &copy; <?= date('Y') ?> - Todos los derechos reservados
    </div>

    <script>
        function toggleSubmenu(id) {
            document.querySelectorAll('.has-submenu').forEach(el => {
                if (el.id !== id) el.classList.remove('active');
            });

            const el = document.getElementById(id);
            el.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    });
    </script>


    <script src="https://kit.fontawesome.com/b696b53ba9.js" crossorigin="anonymous"></script>
</body>

</html>