<?php

namespace Controllers;

use Exception;
use Model\Pesos;
use MVC\Router;
use Model\ActiveRecord;

class PesosController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission(['ADMIN']);
        $router->render('pesos/index', []);
    }

    public static function calcular(Router $router)
    {
        isAuth();
        hasPermission(['ADMIN']);
        $router->render('calcular/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            $peso = new Pesos($_POST);
            $peso->sincronizar($_POST);

            $resultado = $peso->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Porcentaje guardado correctamente'
                ]);
                $db->commit();
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo guardar el porcentaje'
                ]);
                $db->rollBack();
            }
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT p.*, t.tipojoya_nombre_corto 
                    FROM pesos p
                    INNER JOIN tipos_joya t ON p.pesos_tipojoya_id = t.tipojoya_id
                    WHERE p.peso_situacion = 1";

            $pesos = Pesos::fetchArray($sql);

            echo json_encode([
                'codigo' => 1,
                'datos' => $pesos,
                'mensaje' => count($pesos) . ' registro(s) encontrado(s)'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener registros',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            $peso = Pesos::find($_POST['pesos_id']);
            $peso->sincronizar($_POST);
            $peso->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Porcentaje actualizado correctamente'
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar',
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
            $peso = Pesos::find($_POST['pesos_id']);
            $peso->peso_situacion = 0;
            $peso->actualizar();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Registro desactivado correctamente'
            ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

            // Ruta: /API/pesos/porcentaje
        public static function porcentajeAPI() {
            getHeadersApi();
            $tipojoya_id = $_GET['tipojoya_id'] ?? null;
            if (!$tipojoya_id) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'Tipo de joya requerido']);
                return;
            }

            $query = "SELECT peso_tipo, peso_porcentaje 
                    FROM pesos 
                    WHERE pesos_tipojoya_id = $tipojoya_id 
                    AND peso_situacion = 1";

            $resultados = Pesos::fetchArray($query);
            $porcentajes = ['C' => 0, 'V' => 0];

            foreach ($resultados as $r) {
                $porcentajes[$r['peso_tipo']] = floatval($r['peso_porcentaje']);
            }

            echo json_encode([
                'codigo' => 1,
                'porcentajes' => $porcentajes
            ]);
        }

}
