<?php

namespace Controllers;

use Exception;
use Model\Marcas;
use MVC\Router;
use Model\ActiveRecord;

class MarcasController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission([
            'ADMIN',
        ]);
        $router->render('marcas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            if (isset($_POST['marca_nombre'])) {
                $_POST['marca_nombre'] = mb_strtoupper($_POST['marca_nombre']);
            }

            $marca = new Marcas($_POST);
            $marca->sincronizar($_POST);

            $resultado = $marca->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca creada correctamente',
                ]);
                $db->commit();
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo crear la marca. Posible duplicado.',
                ]);
            }

        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al crear marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT * FROM marcas";
            $marcas = Marcas::fetchArray($sql);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'codigo' => 1,
                'datos' => $marcas,
                'mensaje' => count($marcas) . ' marca/s obtenidas',
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'datos' => null,
                'mensaje' => 'Error obteniendo marcas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $marca_id = filter_var($_POST['marca_id'], FILTER_SANITIZE_NUMBER_INT);

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            if (isset($_POST['marca_nombre'])) {
                $_POST['marca_nombre'] = mb_strtoupper($_POST['marca_nombre']);
            }

            $marca = Marcas::find($marca_id);
            $marca->sincronizar($_POST);
            $marca->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marca actualizada',
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar marca',
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
            $marca = Marcas::find($_POST['marca_id']);
            $marca->sincronizar($_POST);
            $marca->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estado de la marca actualizado',
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar estado de marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
