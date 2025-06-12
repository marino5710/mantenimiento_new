<?php

namespace Model;
use PDO;

class Pesos extends ActiveRecord {
    protected static $tabla = 'pesos';
    protected static $idTabla = 'pesos_id';

    protected static $columnasDB = [
        'pesos_tipojoya_id',
        'peso_porcentaje',
        'peso_tipo',
        'peso_situacion',
        'creado_en',
        'actualizado_en'
    ];

    public $pesos_id;
    public $pesos_tipojoya_id;
    public $peso_porcentaje;
    public $peso_tipo;
    public $peso_situacion;
    public $creado_en;
    public $actualizado_en;

    public function __construct($args = []) {
        $this->pesos_id = $args['pesos_id'] ?? null;
        $this->pesos_tipojoya_id = $args['pesos_tipojoya_id'] ?? null;
        $this->peso_porcentaje = $args['peso_porcentaje'] ?? 0.00;
        $this->peso_tipo = $args['peso_tipo'] ?? '';
        $this->peso_situacion = $args['peso_situacion'] ?? 1;
        $this->creado_en = $args['creado_en'] ?? null;
        $this->actualizado_en = $args['actualizado_en'] ?? null;
    }
}
