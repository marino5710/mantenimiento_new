<?php

namespace Controllers;

use Exception;
use Model\Productos;
use MVC\Router;
use Model\ActiveRecord;

class ProductosController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission([
            'ADMIN',
        ]);
        $router->render('productos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        $db = ActiveRecord::getDB();
        $db->beginTransaction();
    
        try {
            if (isset($_POST['producto_nombre'])) {
                $_POST['producto_nombre'] = mb_strtoupper($_POST['producto_nombre']);
            }
            if (isset($_POST['producto_codigo'])) {
                $_POST['producto_codigo'] = mb_strtoupper($_POST['producto_codigo']);
            }
    // echo json_encode($_POST); 
    // exit; 
            $producto = new Productos($_POST);
            $producto->sincronizar($_POST);
    
            // --- PROCESAR HASTA 3 IMÁGENES ---
            for ($i = 1; $i <= 3; $i++) {
                $campo = $i === 1 ? 'producto_imagen' : "producto_imagen{$i}";
    
                if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
                    $archivoTmp = $_FILES[$campo]['tmp_name'];
                    $nombreOriginal = $_FILES[$campo]['name'];
                    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
                    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
                    if (!in_array($extension, $extensionesPermitidas)) {
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => "Archivo $campo no permitido"
                        ]);
                        $db->rollBack();
                        exit;
                    }
    
                    $nombreUnico = uniqid($campo . '_') . '.' . $extension;
                    $rutaDestino = '../public/images/productos/' . $nombreUnico;
    
                    if (!is_uploaded_file($archivoTmp) || !move_uploaded_file($archivoTmp, $rutaDestino)) {
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => "Error al guardar $campo"
                        ]);
                        $db->rollBack();
                        exit;
                    }
    
                    // Asignar al modelo
                    $producto->$campo = $nombreUnico;
                }
            }
    
            $resultado = $producto->crear();
            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Producto creado correctamente'
                ]);
                $db->commit();
            } else {
                $db->rollBack();
                
                // Verificamos si el error fue por código duplicado (MySQL: 1062)
                if (isset($resultado['codigo_sql']) && $resultado['codigo_sql'] == 23000 && str_contains($resultado['error'], 'Duplicate entry')) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Ya existe otro producto con ese código. No se puede crear, modifique el anterior o elimínelo.'
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al crear el producto.',
                        'detalle' => $resultado['error']
                    ]);
                }
            }
            
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error general',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    

    public static function buscarAPI()
    {
        getHeadersApi();
    
        try {
            $sql = "SELECT 
                        p.*,
                        c.categoria_nombre,
                        m.marca_nombre,
                        t.tipojoya_nombre_corto
                    FROM productos p
                    LEFT JOIN categorias c ON p.categoria_id = c.categoria_id
                    LEFT JOIN marcas m ON p.marca_id = m.marca_id
                    LEFT JOIN tipos_joya t ON p.tipojoya_id = t.tipojoya_id
                    WHERE p.producto_situacion = 1";
    
            // --- FILTROS DINÁMICOS ---
            $condiciones = [];
    
            if (!empty($_GET['categoria_id'])) {
                $categoria_id = intval($_GET['categoria_id']);
                $condiciones[] = "p.categoria_id = $categoria_id";
            }
    
            if (!empty($_GET['tipojoya_id'])) {
                $tipojoya_id = intval($_GET['tipojoya_id']);
                $condiciones[] = "p.tipojoya_id = $tipojoya_id";
            }
    
            if (!empty($_GET['marca_id'])) {
                $marca_id = intval($_GET['marca_id']);
                $condiciones[] = "p.marca_id = $marca_id";
            }
    
            if (!empty($condiciones)) {
                $sql .= ' AND ' . implode(' AND ', $condiciones);
            }
    
            $sql .= ' ORDER BY producto_id DESC';
    
            $productos = Productos::fetchArray($sql);
    
            foreach ($productos as &$producto) {
                $producto['producto_imagen_url'] = !empty($producto['producto_imagen'])
                    ? '/destellos_glamour/images/productos/' . $producto['producto_imagen']
                    : '/destellos_glamour/images/sin-imagen.png';
            }
    
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'codigo' => 1,
                'datos' => $productos,
                'mensaje' => count($productos) . ' producto/s obtenidos',
            ], JSON_UNESCAPED_UNICODE);
    
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'datos' => null,
                'mensaje' => 'Error obteniendo productos',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    

    public static function modificarAPI()
    {
        getHeadersApi();
     
        $producto_id = filter_var($_POST['producto_id'], FILTER_SANITIZE_NUMBER_INT);

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            if (isset($_POST['producto_nombre'])) {
                $_POST['producto_nombre'] = mb_strtoupper($_POST['producto_nombre']);
            }
            if (isset($_POST['producto_codigo'])) {
                $_POST['producto_codigo'] = mb_strtoupper($_POST['producto_codigo']);
            }

            $producto = Productos::find($producto_id);
            $producto->sincronizar($_POST);
            $producto->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Producto actualizado',
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        $db = ActiveRecord::getDB();
        $db->beginTransaction();
    
        try {
            $producto_id = $_POST['producto_id'] ?? null;
    
            if (!$producto_id || !is_numeric($producto_id)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de producto no válido'
                ]);
                return;
            }
    
            $producto = Productos::find($producto_id);
    
            if (!$producto) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Producto no encontrado'
                ]);
                return;
            }
    
            if ((int)$producto->producto_stock > 0) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => "El producto tiene stock disponible. Registre un egreso antes de eliminarlo."
                ]);
                return;
            }
    
            // Borrar imágenes físicas
            for ($i = 1; $i <= 3; $i++) {
                $campo = $i === 1 ? 'producto_imagen' : "producto_imagen{$i}";
                if (!empty($producto->$campo)) {
                    $ruta = '../public/images/productos/' . $producto->$campo;
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                    $producto->$campo = null; // quitar referencia en la BD
                }
            }
    
            // Cambiar estado a inactivo
            $producto->producto_situacion = 0;
            $producto->actualizar();
    
            $db->commit();
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Producto eliminado correctamente (estado inactivo, imágenes borradas)'
            ]);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar producto',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    

    public static function actualizarImagen()
    {
        getHeadersApi();
        $db = ActiveRecord::getDB();
        $db->beginTransaction();
    
        try {
            $producto_id = $_POST['producto_id'] ?? null;
    
            if (!$producto_id || !is_numeric($producto_id)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de producto no válido'
                ]);
                exit;
            }
    
            $producto = Productos::find($producto_id);
    
            if (!$producto) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Producto no encontrado'
                ]);
                exit;
            }
    
            // Procesar hasta 3 imágenes
            for ($i = 1; $i <= 3; $i++) {
                $campo = $i === 1 ? 'producto_imagen' : "producto_imagen{$i}";
    
                if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
                    $archivoTmp = $_FILES[$campo]['tmp_name'];
                    $nombreOriginal = $_FILES[$campo]['name'];
                    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
                    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
                    if (!in_array($extension, $extensionesPermitidas)) {
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => "El archivo de $campo no tiene una extensión permitida"
                        ]);
                        $db->rollBack();
                        exit;
                    }
    
                    $nombreUnico = uniqid($campo . '_') . '.' . $extension;
                    $rutaDestino = '../public/images/productos/' . $nombreUnico;
    
                    // Eliminar imagen anterior si existe
                    if (!empty($producto->$campo)) {
                        $rutaAnterior = '../public/images/productos/' . $producto->$campo;
                        if (file_exists($rutaAnterior)) {
                            unlink($rutaAnterior);
                        }
                    }
    
                    if (!move_uploaded_file($archivoTmp, $rutaDestino)) {
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => "Error al guardar $campo"
                        ]);
                        $db->rollBack();
                        exit;
                    }
    
                    // Asignar nuevo nombre
                    $producto->$campo = $nombreUnico;
                }
            }
    
            $producto->actualizar();
    
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Imágenes actualizadas correctamente'
            ]);
            $db->commit();
    
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar imagen',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    

}
