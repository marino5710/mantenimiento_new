<?php

namespace Model;
use PDO;
class Usuarios extends ActiveRecord {
    // Nombre de la tabla y su clave primaria
    protected static $tabla = 'usuarios';
    protected static $idTabla = 'usuario_id';

    // Columnas de la base de datos que utilizaremos en el modelo
    protected static $columnasDB = [
        'usuario_nombre', 
        'usuario_apellido', 
        'usuario_correo', 
        'usuario_contrasena', 
        'usuario_dpi', 
        'usuario_nit', 
        'usuario_situacion', 
        'rol_id', 
        'creado_en', 
        'actualizado_en'
    ];

    // Atributos del modelo
    public $usuario_id;
    public $usuario_nombre;
    public $usuario_apellido;
    public $usuario_correo;
    public $usuario_contrasena;
    public $usuario_dpi;
    public $usuario_nit;
    public $usuario_situacion;
    public $rol_id;
    public $creado_en;
    public $actualizado_en;
    

    // Constructor
    public function __construct($args = []) {
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->usuario_nombre =  $args['usuario_nombre'] ?? '';
        $this->usuario_apellido = $args['usuario_apellido'] ?? '';
        $this->usuario_correo = $args['usuario_correo'] ?? '';
        $this->usuario_contrasena = $args['usuario_contrasena'] ?? '';  // La contraseña será hasheada
        $this->usuario_dpi = $args['usuario_dpi'] ?? '';
        $this->usuario_nit = $args['usuario_nit'] ?? '';
        $this->usuario_situacion = $args['usuario_situacion'] ?? 1;  // Activo por defecto
        $this->rol_id = !empty($args['rol_id']) ? intval($args['rol_id']) : null;
        $this->creado_en = $args['creado_en'] ?? null;
        $this->actualizado_en = $args['actualizado_en'] ?? '';

    }

    // Método para validar si el correo ya existe
// Método para validar si el correo ya existe
public function existeUsuario(): bool {
    // Usamos quote() en PDO para evitar inyecciones SQL
    $correoEscapado = self::$db->quote($this->usuario_correo);
    $query = "SELECT * FROM " . static::$tabla . " WHERE usuario_correo = " . $correoEscapado . " LIMIT 1";

    $resultado = self::$db->query($query);

    if ($resultado) {
        $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
        // Si encuentra al menos un registro, entonces ya existe el usuario
        return $usuario !== false;
    }

    return false;
}

public function obtenerUsuarioIdPorCorreo($correo): ?int {
    // Usamos quote() en PDO para evitar inyecciones SQL
    $correoEscapado = self::$db->quote($correo);
    $query = "SELECT usuario_id FROM " . static::$tabla . " WHERE usuario_correo = " . $correoEscapado . " LIMIT 1";

    $resultado = self::$db->query($query);

    if ($resultado) {
        $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
        // Si encuentra el usuario, devolver el usuario_id
        return $usuario ? intval($usuario['usuario_id']) : null;
    }

    return null;
}

// Método para validar si el DPI ya existe
public function existeDPI(): bool {
    if (empty($this->usuario_dpi)) {
        return false; // No se proporciona DPI
    }

    $dpiEscapado = self::$db->quote($this->usuario_dpi);
    $query = "SELECT * FROM " . static::$tabla . " WHERE usuario_dpi = " . $dpiEscapado . " LIMIT 1";

    $resultado = self::$db->query($query);

    if ($resultado) {
        $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
        return $usuario !== false;
    }

    return false;
}

// Método para validar si el NIT ya existe
public function existeNIT(): bool {
    if (empty($this->usuario_nit)) {
        return false; // No se proporciona NIT
    }

    $nitEscapado = self::$db->quote($this->usuario_nit);
    $query = "SELECT * FROM " . static::$tabla . " WHERE usuario_nit = " . $nitEscapado . " LIMIT 1";

    $resultado = self::$db->query($query);

    if ($resultado) {
        $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
        return $usuario !== false;
    }

    return false;
}



    // Método para hashear la contraseña antes de guardarla
    public function hashPassword() {
        $this->usuario_contrasena = password_hash($this->usuario_contrasena, PASSWORD_BCRYPT);
    }

    // Método para verificar la contraseña
    public function comprobarPassword($passwordIngresada) {
        return password_verify($passwordIngresada, $this->usuario_contrasena);
    }
    
    // Método para cambiar la situación del usuario (activar/desactivar)
    public function cambiarSituacion($situacion) {
        $this->usuario_situacion = $situacion;
    }
}
