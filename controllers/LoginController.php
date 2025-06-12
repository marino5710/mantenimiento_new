<?php

namespace Controllers;

use MVC\Router;
use Model\Usuarios;
use Model\ActiveRecord;

class LoginController
{
    // Renderiza la vista de login
    public static function index(Router $router)
    {
        isNotAuth(); // Verifica si el usuario no está autenticado
        $router->render('login/index', [], 'login_layout');

    }
    public static function autenticar()
    {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';


            // Validar que los campos no estén vacíos
            if (empty($usuario) || empty($password) || empty($recaptchaResponse)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Todos los campos son obligatorios.',
                ]);
                exit();
            }


            // // Verificar el reCAPTCHA
            // $recaptchaSecret = '6LdfY2kqAAAAANzOwyWEspFqK5b5afNIkhLFfzAu'; // Reemplaza con tu clave secreta de reCAPTCHA
            // $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
            // $recaptchaData = [
            //     'secret' => $recaptchaSecret,
            //     'response' => $recaptchaResponse,
            // ];

            // $options = [
            //     'http' => [
            //         'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            //         'method' => 'POST',
            //         'content' => http_build_query($recaptchaData),
            //     ],
            // ];
            // $context = stream_context_create($options);
            // $result = file_get_contents($recaptchaUrl, false, $context);
            // $result = json_decode($result, true);

            // if (!$result['success']) {
            //     header('Content-Type: application/json');
            //     echo json_encode([
            //         'codigo' => 0,
            //         'mensaje' => 'Error en la verificación del reCAPTCHA.',
            //     ]);
            //     exit();
            // }

            // Iniciar sesión si aún no ha sido iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Verificar usuario y contraseña
            $usuarioDBArray = Usuarios::where('usuario_correo', $usuario);

            if (!empty($usuarioDBArray)) {
                // Asegúrate de que la instancia obtenida sea un objeto de Usuarios
                $usuarioDB = array_shift($usuarioDBArray);

                // Verificar que $usuarioDB sea instancia del modelo Usuarios
                if ($usuarioDB instanceof Usuarios) {

                    // Verificar que el usuario esté activo (usuario_situacion = 1)
                    if ($usuarioDB->usuario_situacion != 1) {
                        // Usuario desactivado
                        header('Content-Type: application/json');
                        echo json_encode([
                            'codigo' => 2,
                            'mensaje' => 'Usuario desactivado. No tiene acceso.',
                        ]);
                        exit();
                    }
                    // Verificar la contraseña usando la función del modelo
                    if ($usuarioDB->comprobarPassword($password)) {
                        // Login exitoso
                        $_SESSION['login'] = true;
                        $_SESSION['usuario'] = $usuarioDB->usuario_correo;
                        $_SESSION['usuario_id'] = $usuarioDB->usuario_id; // Agregamos el ID del usuario


                        // Obtener el rol y sus permisos
                        $rolQuery = "SELECT r.rol_clave 
    FROM roles r 
    JOIN usuarios u ON u.rol_id = r.rol_id 
    WHERE u.usuario_id = ? AND r.rol_situacion = 1";

                        $stmt = ActiveRecord::getDB()->prepare($rolQuery);
                        $stmt->execute([$usuarioDB->usuario_id]);
                        $rol_clave = $stmt->fetchColumn();

                        // Establecer el permiso en la sesión según el rol
                        if ($rol_clave) {
                            $_SESSION[$rol_clave] = true;
                        }


                        // Responder con JSON indicando éxito y URL de redirección
                        header('Content-Type: application/json');
                        echo json_encode([
                            'codigo' => 1,
                            'mensaje' => 'Login exitoso',
                            'redireccion' => $_ENV['APP_REDIRECTION']
                        ]);
                        exit();
                    } else {
                        // Contraseña incorrecta
                        header('Content-Type: application/json');
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'Contraseña incorrecta',
                        ]);
                        exit();
                    }
                } else {
                    // Error: No se obtuvo un objeto Usuarios
                    header('Content-Type: application/json');
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al procesar el usuario.',
                    ]);
                    exit();
                }
            } else {
                // Usuario no encontrado
                header('Content-Type: application/json');
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado',
                ]);
                exit();
            }
        }
    }


    // Método para cerrar sesión
    public static function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit();
    }

}

// namespace Controllers;

// use MVC\Router;
// use Model\Usuarios;
// use Model\ActiveRecord;

// class LoginController
// {

//     // Renderiza la vista de login
//     public static function index(Router $router)
//     {
//         $router->render('login/index', [], 'login_layout');  // Layout personalizado para el login
//     }
//     public static function autenticar(Router $router)
//     {
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             // Recibir datos del formulario
//             $usuario = $_POST['username'] ?? '';
//             $password = $_POST['password'] ?? '';
//             $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

//             // Validar que los campos no estén vacíos
//             if (empty($usuario) || empty($password) || empty($recaptchaResponse)) {
//                 header('Content-Type: application/json');
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'Todos los campos son obligatorios.',
//                 ]);
//                 exit();
//             }

//             // Verificar el reCAPTCHA
//             $recaptchaSecret = '6LdfY2kqAAAAAOzcag4irV4canZMvori97XzGeEw';
//             $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
//             $recaptchaData = [
//                 'secret' => $recaptchaSecret,
//                 'response' => $recaptchaResponse,
//             ];

//             $response = file_get_contents($recaptchaUrl . '?' . http_build_query($recaptchaData));
//             $result = json_decode($response, true);

//             if ($result['success'] === true) {
//                 // Verificar usuario y contraseña
//                 $usuarioDB = Usuarios::where('usuario_correo', $usuario);

//                 if (!empty($usuarioDB)) {
//                     // Acceder al primer (y único) resultado
//                     $usuarioDB = $usuarioDB[0];

//                     // Verificar la contraseña usando la función del modelo
//                     if ($usuarioDB->comprobarPassword($password)) {
//                         // Login exitoso
//                         session_start();
//                         $_SESSION['login'] = true;
//                         $_SESSION['usuario'] = $usuarioDB->usuario_correo;

//                         // Responder con JSON indicando éxito y URL de redirección
//                         header('Content-Type: application/json');
//                         echo json_encode([
//                             'codigo' => 1,
//                             'mensaje' => 'Login exitoso',
//                             'redireccion' => 'http://localhost:9002/control_magictravel'
//                         ]);
//                         exit();
//                     } else {
//                         // Contraseña incorrecta
//                         header('Content-Type: application/json');
//                         echo json_encode([
//                             'codigo' => 0,
//                             'mensaje' => 'Contraseña incorrecta',
//                         ]);
//                         exit();
//                     }
//                 } else {
//                     // Usuario no encontrado
//                     header('Content-Type: application/json');
//                     echo json_encode([
//                         'codigo' => 0,
//                         'mensaje' => 'Usuario no encontrado',
//                     ]);
//                     exit();
//                 }
//             } else {
//                 // reCAPTCHA fallido
//                 header('Content-Type: application/json');
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'Error en la verificación del reCAPTCHA.',
//                 ]);
//                 exit();
//             }
//         }
//     }



// }
// public static function autenticar() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         // Recibir datos del formulario
//         $usuario = $_POST['username'] ?? '';
//         $password = $_POST['password'] ?? '';

//         // Crear una instancia del modelo Usuarios
//         $usuarioModel = new Usuarios(['usuario_correo' => $usuario]);

//         // Verificar si existe el usuario
//         if ($usuarioModel->existeUsuario()) {
//             // El usuario existe, ahora lo obtenemos para verificar la contraseña
//             $usuarioDB = Usuarios::where('usuario_correo', $usuario)[0] ?? null;

//             if ($usuarioDB) {
//                 // Verificar la contraseña
//                 if (password_verify($password, $usuarioDB->usuario_contrasena)) {
//                     // Login exitoso
//                     session_start();
//                     $_SESSION['login'] = true;
//                     $_SESSION['usuario'] = $usuarioDB->usuario_correo;

//                     // Redirigir a la página principal o dashboard
//                     header('Location: /');
//                     exit();
//                 } else {
//                     // Contraseña incorrecta
//                     echo json_encode([
//                         'codigo' => 0,
//                         'mensaje' => 'Contraseña incorrecta',
//                     ]);
//                 }
//             } else {
//                 // Usuario no encontrado
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'Usuario no encontrado',
//                 ]);
//             }
//         } else {
//             // Usuario no encontrado
//             echo json_encode([
//                 'codigo' => 0,
//                 'mensaje' => 'Usuario no encontrado',
//             ]);
//         }
//     }
// }

// // Autenticación a través de la API
// public static function autenticar() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         // Recibir datos del formulario
//         $usuario = $_POST['username'] ?? '';
//         $password = $_POST['password'] ?? '';
//         // $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';


//         // echo json_encode($_POST);
//         // exit; 

//         // // Verificar el reCAPTCHA
//         // $recaptchaSecret = '6LdfY2kqAAAAAOzcag4irV4canZMvori97XzGeEw';
//         // $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
//         // $recaptchaData = [
//         //     'secret' => $recaptchaSecret,
//         //     'response' => $recaptchaResponse,
//         // ];

//         // $response = file_get_contents($recaptchaUrl . '?' . http_build_query($recaptchaData));
//         // $result = json_decode($response, true);

//         // if ($result['success'] === true) {
//             // Verificar usuario y contraseña
//             $usuarioDB = Usuarios::where('usuario_correo', $usuario);

//             // echo json_encode($usuarioDB); 
//             // exit; 

//             if ($usuarioDB && password_verify($password, $usuarioDB->usuario_contrasena)) {
//                 // Login exitoso
//                 session_start();
//                 $_SESSION['login'] = true;
//                 $_SESSION['usuario'] = $usuarioDB->usuario_correo;

//                 // Redirigir a la página principal o dashboard
//                 header('Location: /');
//                 exit();
//             } else {
//                 // Usuario o contraseña incorrectos
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'Usuario o contraseña incorrectos',
//                 ]);
//             }
//         // } else {
//         //     // reCAPTCHA fallido
//         //     echo json_encode([
//         //         'codigo' => 0,
//         //         'mensaje' => 'Error en la verificación del reCAPTCHA.',
//         //     ]);
//         // }
//     }
// }
