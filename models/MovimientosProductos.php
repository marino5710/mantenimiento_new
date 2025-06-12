<?php

namespace Model;

class MovimientosProductos extends ActiveRecord {

    // Nombre de la tabla y clave primaria
    protected static $tabla = 'movimientos_productos';
    protected static $idTabla = 'mov_id';

    // Columnas de la tabla
    protected static $columnasDB = [
        'producto_id',
        'mov_tipo',
        'mov_cantidad',
        'mov_fecha',
        'mov_descripcion'
    ];

    // Atributos del modelo
    public $mov_id;
    public $producto_id;
    public $mov_tipo;
    public $mov_cantidad;
    public $mov_fecha;
    public $mov_descripcion;

    // Constructor
    public function __construct($args = [])
    {
        $this->mov_id = $args['mov_id'] ?? null;
        $this->producto_id = $args['producto_id'] ?? null;
        $this->mov_tipo = $args['mov_tipo'] ?? '';
        $this->mov_cantidad = $args['mov_cantidad'] ?? 0;
        $this->mov_fecha = $args['mov_fecha'] ?? date('Y-m-d H:i:s');
        $this->mov_descripcion = $args['mov_descripcion'] ?? '';
    }
}
