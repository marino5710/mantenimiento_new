<?php


namespace Controllers;
use Exception;
use MVC\Router;
use Mpdf\Mpdf;
use Model\Usuarios;
use Model\ActiveRecord;

class UsuariosController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission([
            'ADMIN',
        ]);
        $router->render('adminusuarios/index', [

        ]);

    }

  

//     public static function guardarAPI()
//     {
//         getHeadersApi();
// // echo json_encode( $_POST); 
// // exit; 
//         $db = ActiveRecord::getDB();
//         $db->beginTransaction();

//         try {
//             // Validar si 'rol_id' está presente en el POST y tiene un valor válido
//             if (empty($_POST['rol_id']) || !is_numeric($_POST['rol_id'])) {
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'El rol es obligatorio y debe ser un valor válido',
//                 ]);
//                 $db->rollBack();
//                 exit;
//             }

//             // Crear instancia del usuario con los datos recibidos
//             $usuario = new Usuarios($_POST);

//             //Comprobar si el usuario ya existe
//             if ($usuario->existeUsuario()) {
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'Ya existe un usuario con el correo proporcionado',
//                 ]);
//                 $db->rollBack();
//                 exit;
//             }

//             // Verificar si ya existe un usuario con el DPI proporcionado
//             if ($usuario->existeDPI()) {
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'Ya existe un usuario con el DPI proporcionado',
//                 ]);
//                 $db->rollBack();
//                 exit;
//             }

//             // Verificar si ya existe un usuario con el NIT proporcionado
//             if ($usuario->existeNIT()) {
//                 echo json_encode([
//                     'codigo' => 0,
//                     'mensaje' => 'Ya existe un usuario con el NIT proporcionado',
//                 ]);
//                 $db->rollBack();
//                 exit;
//             }


//             // Generar la contraseña automáticamente con componentes variados
//             $nombre = $usuario->usuario_nombre;
//             $apellido = $usuario->usuario_apellido;

//             $nombreLimpiado = preg_replace('/[^A-Za-z]/', '', $nombre); // Remover caracteres especiales
//             $apellidoInicial = strtoupper(substr($apellido, 0, 1)); // Primera inicial del apellido
//             $numeroAleatorio = rand(10, 99); // Dos números aleatorios
//             $simbolos = ['#', '*', '%', '$', '.']; // Símbolos para elegir aleatoriamente
//             $simboloAleatorio = $simbolos[array_rand($simbolos)]; // Elegir un símbolo al azar

//             // Componentes para la contraseña
//             $componentes = [
//                 $nombreLimpiado,
//                 $apellidoInicial,
//                 $numeroAleatorio,
//                 $simboloAleatorio
//             ];
            
//             // Barajar aleatoriamente los componentes
//             shuffle($componentes);
            
//             // Contraseña generada uniendo los componentes
//             $passwordGenerada = implode('', $componentes);
            
//             // Asignar la contraseña generada al usuario y hashearla usando el método del modelo
//             $usuario->usuario_contrasena = $passwordGenerada;
//             $usuario->hashPassword();
            
//             // Convertir a mayúsculas y decodificar
//             if (isset($_POST['usuario_nombre'])) {
//                 $_POST['usuario_nombre'] = utf8_decode(mb_strtoupper($_POST['usuario_nombre']));
//             }
            
//             if (isset($_POST['usuario_apellido'])) {
//                 $_POST['usuario_apellido'] = utf8_decode(mb_strtoupper($_POST['usuario_apellido']));
//             }
            
//             if (isset($_POST['usuario_correo'])) {
//                 // Sanitizar el correo electrónico para evitar caracteres no válidos
//                 $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
//             }
            
//             // Sincronizar los datos recibidos (incluyendo 'rol_id')
//             $usuario->sincronizar($_POST);
            
//             // Asignar manualmente valores predeterminados para campos automáticos si están vacíos
//             if (empty($usuario->creado_en)) {
//                 $usuario->creado_en = date('Y-m-d H:i:s');
//             }
            
//             // Asignar manualmente valores predeterminados para campos automáticos si están vacíos
//             if (empty($usuario->actualizado_en)) {
//                 $usuario->actualizado_en = date('Y-m-d H:i:s');
//             }
            
         

//             // Guardar el usuario en la base de datos
//             $usuario->crear();
            
//             // Enviar la respuesta con la contraseña generada
//             echo json_encode([
//                 'codigo' => 1,
//                 'mensaje' => 'Usuario creado correctamente',
//             ]);
            
//             $db->commit();
            
//         } catch (Exception $e) {
//             echo json_encode([
//                 'codigo' => 0,
//                 'mensaje' => 'Error al crear un usuario',
//                 'detalle' => $e->getMessage()
//             ]);
//             $db->rollBack();
//         }
//     }


    public static function guardarAPI()
    {
        getHeadersApi();
       
        
        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            // Validar si 'rol_id' está presente en el POST y tiene un valor válido
            if (empty($_POST['rol_id']) || !is_numeric($_POST['rol_id'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El rol es obligatorio y debe ser un valor válido',
                ]);
                $db->rollBack();
                exit;
            }

            // Crear instancia del usuario con los datos recibidos
            $usuario = new Usuarios($_POST);

            //Comprobar si el usuario ya existe
            if ($usuario->existeUsuario()) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario con el correo proporcionado',
                ]);
                $db->rollBack();
                exit;
            }

            // Verificar si ya existe un usuario con el DPI proporcionado
            if ($usuario->existeDPI()) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario con el DPI proporcionado',
                ]);
                $db->rollBack();
                exit;
            }

            // Verificar si ya existe un usuario con el NIT proporcionado
            if ($usuario->existeNIT()) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario con el NIT proporcionado',
                ]);
                $db->rollBack();
                exit;
            }


            // Generar la contraseña automáticamente con componentes variados
            $nombre = $usuario->usuario_nombre;
            $apellido = $usuario->usuario_apellido;

            $nombreLimpiado = preg_replace('/[^A-Za-z]/', '', $nombre); // Remover caracteres especiales
            $apellidoInicial = strtoupper(substr($apellido, 0, 1)); // Primera inicial del apellido
            $numeroAleatorio = rand(10, 99); // Dos números aleatorios
            $simbolos = ['#', '*', '%', '$', '.']; // Símbolos para elegir aleatoriamente
            $simboloAleatorio = $simbolos[array_rand($simbolos)]; // Elegir un símbolo al azar

            // Componentes para la contraseña
            $componentes = [
                $nombreLimpiado,
                $apellidoInicial,
                $numeroAleatorio,
                $simboloAleatorio
            ];
            
            // Barajar aleatoriamente los componentes
            shuffle($componentes);
            
            // Contraseña generada uniendo los componentes
            $passwordGenerada = implode('', $componentes);
            
            
            // Convertir a mayúsculas y decodificar
            if (isset($_POST['usuario_nombre'])) {
                $_POST['usuario_nombre'] = utf8_decode(mb_strtoupper($_POST['usuario_nombre']));
            }
            
            if (isset($_POST['usuario_apellido'])) {
                $_POST['usuario_apellido'] = utf8_decode(mb_strtoupper($_POST['usuario_apellido']));
            }
            
            if (isset($_POST['usuario_correo'])) {
                // Sanitizar el correo electrónico para evitar caracteres no válidos
                $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
            }
            
            // Sincronizar los datos recibidos (incluyendo 'rol_id')
            $usuario->sincronizar($_POST);
            // Asignar la contraseña generada al usuario y hashearla usando el método del modelo
            $usuario->usuario_contrasena = $passwordGenerada;
            $usuario->hashPassword();
            
            // Asignar manualmente valores predeterminados para campos automáticos si están vacíos
            if (empty($usuario->creado_en)) {
                $usuario->creado_en = date('Y-m-d H:i:s');
            }
            
            // Asignar manualmente valores predeterminados para campos automáticos si están vacíos
            if (empty($usuario->actualizado_en)) {
                $usuario->actualizado_en = date('Y-m-d H:i:s');
            }
            
            
            $resultado = $usuario->crear();

            if (!$resultado) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error: el usuario no se guardó en la base de datos',
                ]);
                $db->rollBack();
                exit;
            }
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario creado correctamente',
            ]);
            
            $db->commit();
            
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al crear un usuario',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
        }
    }


    public static function generarPassword(Router $router) {
        getHeadersApi();
    
        $usuarioID = $_GET['usuario'] ?? '';
    
        // Verificar si se envió el ID de usuario
        if (!$usuarioID) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de usuario no proporcionado',
            ]);
            exit;
        }
    
        $db = ActiveRecord::getDB();
        $db->beginTransaction();
    
        try {
            // Encontrar el usuario
            $usuario = Usuarios::find($usuarioID);
    
            if (!$usuario) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado',
                ]);
                $db->rollBack();
                exit;
            }
    
            // Generar la nueva contraseña
            $nombre = $usuario->usuario_nombre;
            $apellido = $usuario->usuario_apellido;
    
            $nombreLimpiado = preg_replace('/[^A-Za-z]/', '', $nombre);
            $apellidoInicial = strtoupper(substr($apellido, 0, 1));
            $numeroAleatorio = rand(10, 99);
            $simbolos = ['#', '*', '%', '$', '.'];
            $simboloAleatorio = $simbolos[array_rand($simbolos)];
    
            $componentes = [
                $nombreLimpiado,
                $apellidoInicial,
                $numeroAleatorio,
                $simboloAleatorio
            ];
    
            shuffle($componentes);
            $passwordGenerada = implode('', $componentes);
    
            // Actualizar la contraseña del usuario
            $usuario->usuario_contrasena = $passwordGenerada;
            $usuario->hashPassword();
            $usuario->actualizar();
    
            $db->commit();
    
            // Ahora generar el PDF
            self::reportePDF($router, $usuarioID, $passwordGenerada);
    
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al generar la contraseña',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
        }
    }


    public static function reportePDF(Router $router, $usuarioID, $passwordGenerada = '') {
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

        getHeadersApi();
    
        $sql = "SELECT * FROM usuarios 
                 WHERE usuario_situacion = 1 
                        AND usuario_id = $usuarioID";
    
        try {
            $usuarios = Usuarios::fetchArray($sql);

         

    
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'orientation' => 'P',
                'default_font' => 'ARIAL',
                'margin_bottom' => '35',
            ]);
    
            $header = $router->load('passpdf/header');
            $footer = $router->load('passpdf/footer');
            $html = $router->load('passpdf/password', [
                'usuarios' => $usuarios,
                'passwordGenerada' => $passwordGenerada  // Pasar la contraseña generada al HTML
            ]);
    
            $altura = $mpdf->_getHtmlHeight($header);
            $mpdf->SetTopMargin($altura + 5);
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->WriteHTML($html);
    
            $mpdf->Output();
    
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'datos' => null,
                'mensaje' => 'Error generando el reporte: ' . $e->getMessage(),
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    

    public static function buscarRolesAPI()
    {


        $sql = "SELECT  * FROM roles where rol_situacion = 1";


        try {
            $roles = Usuarios::fetchArray($sql);


            header('Content-Type: application/json');


            echo json_encode($roles);
        } catch (Exception $e) {

            echo json_encode([]);
        }
    }


    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT usuarios.usuario_id,
            usuarios.usuario_nombre,
            usuarios.usuario_apellido,
            usuarios.usuario_correo,
            usuarios.usuario_dpi,
            usuarios.usuario_nit,
            usuarios.usuario_situacion,
            usuarios.creado_en,
            usuarios.actualizado_en,
            roles.rol_nombre, 
            roles.rol_id
     FROM usuarios
     INNER JOIN roles ON usuarios.rol_id = roles.rol_id";

            $usuarios = Usuarios::fetchArray($sql);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'codigo' => 1,
                'datos' => $usuarios,
                'mensaje' => count($usuarios) . ' usuario/s obtenidos',
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'datos' => null,
                'mensaje' => 'Error obteniendo usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }



    public static function modificarAPI()
    {
        getHeadersApi();

        $usuario_id = filter_var($_POST['usuario_id'], FILTER_SANITIZE_NUMBER_INT);

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {

            $usuario = Usuarios::find($usuario_id);

            // Convertir a mayúsculas y decodificar
            if (isset($_POST['usuario_nombre'])) {
                $_POST['usuario_nombre'] = mb_strtoupper($_POST['usuario_nombre']);
            }

            if (isset($_POST['usuario_apellido'])) {
                $_POST['usuario_apellido'] = mb_strtoupper($_POST['usuario_apellido']);
            }

            if (isset($_POST['usuario_correo'])) {
                // Sanitizar el correo electrónico para evitar caracteres no válidos
                $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
            }

            $usuario->sincronizar($_POST);


            // Asignar manualmente valores predeterminados para campos automáticos si están vacíos
            // if (empty($usuario->actualizado_en)) {
            //     $usuario->actualizado_en = date('Y-m-d H:i:s');
            // }
            $usuario->actualizar();


            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario actualizado',
            ]);
            $db->commit();



        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al ingresar usuario',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
        }
    }


    public static function eliminarAPI(){
        getHeadersApi();
        // hasPermissionApi(['SIC_ADMIN','SIC_D1']);
        $db = ActiveRecord::getDB();
        $db->beginTransaction();
        try {     
            $usuario = Usuarios::find($_POST['usuario_id']);
            $usuario->sincronizar($_POST);

            $usuario->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estado del usuario actualizado',
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar estado',
                'detalle' => $e->getMessage()
            ]);
        }
    }

}


