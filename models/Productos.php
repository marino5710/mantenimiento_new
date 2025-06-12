<?php 

namespace Model;
use PDO;

class Productos extends ActiveRecord {
    protected static $tabla = 'productos';
    protected static $idTabla = 'producto_id';

    protected static $columnasDB = [
        'producto_codigo',
        'producto_nombre',
        'producto_descripcion',
        'producto_precio',
        'producto_precio_compra',
        'producto_precio_venta', 
        'producto_stock',
        'producto_imagen',
        'categoria_id',
        'marca_id',
        'producto_situacion',
        'creado_en',
        'actualizado_en'
    ];

    public $producto_id;
    public $producto_codigo;
    public $producto_nombre;
    public $producto_descripcion;
    public $producto_precio;
    public $producto_precio_venta;
    public $producto_precio_compra;
    public $producto_stock;
    public $producto_imagen;
    public $categoria_id;
    public $marca_id;
    public $producto_situacion;
    public $creado_en;
    public $actualizado_en;

    public function __construct($args = []) {
        $this->producto_id = $args['producto_id'] ?? null;
        $this->producto_codigo = $args['producto_codigo'] ?? '';
        $this->producto_nombre = $args['producto_nombre'] ?? '';
        $this->producto_descripcion = $args['producto_descripcion'] ?? '';
        $this->producto_precio = $args['producto_precio'] ?? 0.00;
        $this->producto_peso = $args['producto_peso'] ?? 0.00;
        $this->producto_precio_venta = $args['producto_precio_venta'] ?? 0.00;
        $this->producto_precio_compra = $args['producto_precio_compra'] ?? 0.00;
        $this->producto_stock = $args['producto_stock'] ?? 0;
        $this->producto_imagen = $args['producto_imagen'] ?? '';
        $this->categoria_id = $args['categoria_id'] ?? null;
        $this->marca_id = $args['marca_id'] ?? null;
        $this->producto_situacion = $args['producto_situacion'] ?? 1;
        $this->creado_en = $args['creado_en'] ?? null;
        $this->actualizado_en = $args['actualizado_en'] ?? null;
    }
}
