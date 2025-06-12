<?php

namespace Controllers;

use Exception;
use Model\TiposJoya;
use MVC\Router;
use Model\ActiveRecord;

class TiposJoyaController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission([
            'ADMIN',
        ]);
        $router->render('tiposjoya/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            if (isset($_POST['tipojoya_nombre_corto'])) {
                $_POST['tipojoya_nombre_corto'] = mb_strtoupper($_POST['tipojoya_nombre_corto']);
            }

            $tipojoya = new TiposJoya($_POST);
            $tipojoya->sincronizar($_POST);

            $resultado = $tipojoya->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Tipo de joya creado correctamente',
                ]);
                $db->commit();
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo crear el tipo de joya. Posible duplicado.',
                ]);
            }

        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al crear tipo de joya',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT * FROM tipos_joya";
            $tipos = TiposJoya::fetchArray($sql);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'codigo' => 1,
                'datos' => $tipos,
                'mensaje' => count($tipos) . ' tipo/s de joya obtenidos',
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'datos' => null,
                'mensaje' => 'Error obteniendo tipos de joya',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $tipojoya_id = filter_var($_POST['tipojoya_id'], FILTER_SANITIZE_NUMBER_INT);

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            if (isset($_POST['tipojoya_nombre_corto'])) {
                $_POST['tipojoya_nombre_corto'] = mb_strtoupper($_POST['tipojoya_nombre_corto']);
            }

            $tipojoya = TiposJoya::find($tipojoya_id);
            $tipojoya->sincronizar($_POST);
            $tipojoya->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tipo de joya actualizado',
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar tipo de joya',
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
            $tipojoya = TiposJoya::find($_POST['tipojoya_id']);
            $tipojoya->sincronizar($_POST);
            $tipojoya->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estado del tipo de joya actualizado',
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar estado de tipo de joya',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
