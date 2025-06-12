<?php 

namespace Model;
use PDO;

class Categorias extends ActiveRecord {
    protected static $tabla = 'categorias';
    protected static $idTabla = 'categoria_id';

    protected static $columnasDB = [
        'categoria_nombre', 
        'categoria_descripcion',
        'categoria_situacion'
    ];

    public $categoria_id;
    public $categoria_nombre;
    public $categoria_descripcion;
    public $categoria_situacion;

    public function __construct($args = []) {
        $this->categoria_id = $args['categoria_id'] ?? null;
        $this->categoria_nombre = $args['categoria_nombre'] ?? '';
        $this->categoria_descripcion = $args['categoria_descripcion'] ?? '';
        $this->categoria_situacion = $args['categoria_situacion'] ?? 1;
    }
}
