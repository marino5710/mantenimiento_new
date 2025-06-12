<?php 
require_once __DIR__ . '/../includes/app.php';


use Controllers\CategoriasController;
use Controllers\LoginController;
use Controllers\MarcasController;
use Controllers\MovimientosProductosController;
use Controllers\ProductosController;
use Controllers\TiposJoyaController;
use Controllers\UsuariosController;
use Controllers\PesosController; 
use Model\MovimientosProductos;
use MVC\Router;
use Controllers\AppController;
$router = new Router();
$router->setBaseURL('/');

$router->get('/', [AppController::class,'index']);

//usuarios
$router->get('/adminusuarios', [UsuariosController::class,'index']);
$router->get('/API/adminusuarios/buscarRoles', [UsuariosController::class,'buscarRolesAPI']);
$router->get('/API/adminusuarios/buscar', [UsuariosController::class,'buscarAPI']);
$router->post('/API/adminusuarios/guardar', [UsuariosController::class,'guardarAPI']);
$router->post('/API/adminusuarios/modificar', [UsuariosController::class,'modificarAPI']);
$router->post('/API/adminusuarios/eliminar', [UsuariosController::class,'eliminarAPI']);
$router->get('/password/imprimir', [UsuariosController::class,'generarPassword']);

//categorias
$router->get('/categorias', [CategoriasController::class, 'index']);
$router->post('/API/categorias/guardar', [CategoriasController::class, 'guardarAPI']);
$router->get('/API/categorias/buscar', [CategoriasController::class, 'buscarAPI']);
$router->post('/API/categorias/modificar', [CategoriasController::class, 'modificarAPI']);
$router->post('/API/categorias/eliminar', [CategoriasController::class, 'eliminarAPI']);


//marcas
$router->get('/marcas', [MarcasController::class, 'index']);
$router->post('/API/marcas/guardar', [MarcasController::class, 'guardarAPI']);
$router->get('/API/marcas/buscar', [MarcasController::class, 'buscarAPI']);
$router->post('/API/marcas/modificar', [MarcasController::class, 'modificarAPI']);
$router->post('/API/marcas/eliminar', [MarcasController::class, 'eliminarAPI']);


//tiposjoya


$router->get('/tiposjoya', [TiposJoyaController::class, 'index']);
$router->post('/API/tiposjoya/guardar', [TiposJoyaController::class, 'guardarAPI']);
$router->get('/API/tiposjoya/buscar', [TiposJoyaController::class, 'buscarAPI']);
$router->post('/API/tiposjoya/modificar', [TiposJoyaController::class, 'modificarAPI']);
$router->post('/API/tiposjoya/eliminar', [TiposJoyaController::class, 'eliminarAPI']);

//productos 

$router->get('/productos', [ProductosController::class, 'index']);
$router->post('/API/productos/guardar', [ProductosController::class, 'guardarAPI']);
$router->get('/API/productos/buscar', [ProductosController::class, 'buscarAPI']);
$router->post('/API/productos/modificar', [ProductosController::class, 'modificarAPI']);
$router->post('/API/productos/eliminar', [ProductosController::class, 'eliminarAPI']);
$router->post('/API/productos/imagen', [ProductosController::class, 'actualizarImagen']);

$router->post('/API/movimientos/guardar', [MovimientosProductosController::class, 'registrarAPI']);
$router->get('/API/movimientos/buscar', [MovimientosProductosController::class, 'buscarAPI']);

//PESOS
$router->get('/pesos', [PesosController::class, 'index']);
$router->post('/API/pesos/guardar', [PesosController::class, 'guardarAPI']);
$router->get('/API/pesos/buscar', [PesosController::class, 'buscarAPI']);
$router->get('/API/pesos/porcentaje', [PesosController::class, 'porcentajeAPI']);
$router->post('/API/pesos/modificar', [PesosController::class, 'modificarAPI']);
$router->post('/API/pesos/eliminar', [PesosController::class, 'eliminarAPI']);

$router->get('/calcular', [PesosController::class, 'calcular']);

//login 
$router->get('/login', [LoginController::class,'index']);
$router->post('/API/login/buscar', [LoginController::class,'autenticar']);
$router->get('/logout', [LoginController::class, 'logout']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
