<?php 

namespace Model;
use PDO;

class Marcas extends ActiveRecord {
    protected static $tabla = 'marcas';
    protected static $idTabla = 'marca_id';

    protected static $columnasDB = [
        'marca_nombre', 
        'marca_descripcion',
        'marca_situacion'
    ];

    public $marca_id;
    public $marca_nombre;
    public $marca_descripcion;
    public $marca_situacion;

    public function __construct($args = []) {
        $this->marca_id = $args['marca_id'] ?? null;
        $this->marca_nombre = $args['marca_nombre'] ?? '';
        $this->marca_descripcion = $args['marca_descripcion'] ?? '';
        $this->marca_situacion = $args['marca_situacion'] ?? 1;
    }
}
