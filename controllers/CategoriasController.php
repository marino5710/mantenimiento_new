<?php

namespace Controllers;

use Exception;
use Model\Categorias;
use MVC\Router;
use Model\ActiveRecord;

class CategoriasController
{
    public static function index(Router $router)
    {
        isAuth();
        // hasPermission([
        //     'ADMIN',
        // ]);
        $router->render('categorias/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            if (isset($_POST['categoria_nombre'])) {
                $_POST['categoria_nombre'] = mb_strtoupper($_POST['categoria_nombre']);
            }

            $categoria = new Categorias($_POST);
            $categoria->sincronizar($_POST);

            $resultado = $categoria->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Categoría creada correctamente',
                ]);
                $db->commit();
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo crear la categoría. Posible duplicado de nombre.',
                ]);
            }
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al crear categoría',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT * FROM categorias";
            $categorias = Categorias::fetchArray($sql);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'codigo' => 1,
                'datos' => $categorias,
                'mensaje' => count($categorias) . ' categoría/s obtenidas',
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'datos' => null,
                'mensaje' => 'Error obteniendo categorías',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $categoria_id = filter_var($_POST['categoria_id'], FILTER_SANITIZE_NUMBER_INT);

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            if (isset($_POST['categoria_nombre'])) {
                $_POST['categoria_nombre'] = mb_strtoupper($_POST['categoria_nombre']);
            }

            $categoria = Categorias::find($categoria_id);
            $categoria->sincronizar($_POST);
            $categoria->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Categoría actualizada',
            ]);
            $db->commit();
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar categoría',
                'detalle' => $e->getMessage()
            ]);
            $db->rollBack();
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            $categoria = Categorias::find($_POST['categoria_id']);
            $categoria->sincronizar($_POST);
            $categoria->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estado de la categoría actualizado',
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar estado de categoría',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
