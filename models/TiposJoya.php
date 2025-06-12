<?php 

namespace Model;
use PDO;

class TiposJoya extends ActiveRecord {
    protected static $tabla = 'tipos_joya';
    protected static $idTabla = 'tipojoya_id';

    protected static $columnasDB = [
        'tipojoya_nombre_corto',
        'tipojoya_nombre_largo',
        'tipojoya_descripcion',
        'tipojoya_situacion'
    ];

    public $tipojoya_id;
    public $tipojoya_nombre_corto;
    public $tipojoya_nombre_largo;
    public $tipojoya_descripcion;
    public $tipojoya_situacion;

    public function __construct($args = []) {
        $this->tipojoya_id = $args['tipojoya_id'] ?? null;
        $this->tipojoya_nombre_corto = $args['tipojoya_nombre_corto'] ?? '';
        $this->tipojoya_nombre_largo = $args['tipojoya_nombre_largo'] ?? '';
        $this->tipojoya_descripcion = $args['tipojoya_descripcion'] ?? '';
        $this->tipojoya_situacion = $args['tipojoya_situacion'] ?? 1;
    }
}
