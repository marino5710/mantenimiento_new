<?php

namespace Controllers;

use MVC\Router;
use Model\MovimientosProductos;
use Model\Productos;
use Model\ActiveRecord;
use Exception;

class MovimientosProductosController
{
    public static function registrarAPI()
    {

        isAuth();
        hasPermission([
            'ADMIN',
        ]);
        getHeadersApi();

        $db = ActiveRecord::getDB();
        $db->beginTransaction();

        try {
            $mov = new MovimientosProductos($_POST);
            $mov->sincronizar($_POST);

            // Validar tipo
            if (!in_array($mov->mov_tipo, ['I', 'E'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Tipo de movimiento inválido'
                ]);
                return;
            }

            // Buscar producto
            $producto = Productos::find($mov->producto_id);
            if (!$producto) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Producto no encontrado'
                ]);
                return;
            }

            // Validar stock para egreso
            if ($mov->mov_tipo === 'E' && $producto->producto_stock < $mov->mov_cantidad) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Stock insuficiente para realizar el egreso'
                ]);
                return;
            }

            // Guardar movimiento
            $mov->crear();

            // Actualizar stock del producto
            $producto->producto_stock = ($mov->mov_tipo === 'I')
                ? $producto->producto_stock + $mov->mov_cantidad
                : $producto->producto_stock - $mov->mov_cantidad;

            $producto->actualizar();

            $db->commit();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Movimiento registrado correctamente',
            ]);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar movimiento',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
{
    getHeadersApi();

    $producto_id = $_GET['producto_id'] ?? null;

    if (!$producto_id || !is_numeric($producto_id)) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'ID de producto inválido o no proporcionado',
            'datos' => []
        ]);
        return;
    }

    try {
        $sql = "SELECT mov_id, producto_id, mov_tipo, mov_cantidad, mov_fecha, mov_descripcion
                FROM movimientos_productos
                WHERE producto_id = $producto_id
                ORDER BY mov_fecha DESC";

        $movimientos = MovimientosProductos::fetchArray($sql);

        echo json_encode([
            'codigo' => 1,
            'mensaje' => count($movimientos) . ' movimiento(s) encontrados',
            'datos' => $movimientos
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Error al obtener los movimientos',
            'detalle' => $e->getMessage(),
            'datos' => []
        ]);
    }
}

}

