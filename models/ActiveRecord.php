<?php
namespace Model;
use PDO;
class ActiveRecord
{

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    protected static $idTabla = '';

    // Alertas y Mensajes
    protected static $alertas = [];

    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje;
    }
    // Validación
    public static function getAlertas()
    {
        return static::$alertas;
    }

    public function validar()
    {
        static::$alertas = [];
        return static::$alertas;
    }

    // Registros - CRUD
    public function guardar()
    {
        $resultado = '';
        $id = static::$idTabla ?? 'id';
        if (!is_null($this->$id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);

        // debuguear($resultado);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id = [])
    {
        $idQuery = static::$idTabla ?? 'id';
        $query = "SELECT * FROM " . static::$tabla;

        if (is_array(static::$idTabla)) {
            foreach (static::$idTabla as $key => $value) {
                if ($value == reset(static::$idTabla)) {
                    $query .= " WHERE $value = " . self::$db->quote($id[$value]);
                } else {
                    $query .= " AND $value = " . self::$db->quote($id[$value]);

                }
            }
        } else {

            $query .= " WHERE $idQuery = $id";
        }

        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Obtener Registro
    public static function get($limite)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT ${limite}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor, $condicion = '=')
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ${columna} ${condicion} '${valor}'";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // SQL para Consultas Avanzadas.
    public static function SQL($consulta)
    {
        $query = $consulta;
        $resultado = self::$db->query($query);
        return $resultado;
    }

//  // crea un nuevo registro
//  public function crear()
//  {
//      try {
//          // Sanitizar los datos
//          $atributos = $this->sanitizarAtributos();

//          // Insertar en la base de datos
//          $query = " INSERT INTO " . static::$tabla . " ( ";
//          $query .= join(', ', array_keys($atributos));
//          $query .= " ) VALUES (";
//          $query .= join(", ", array_values($atributos));
//          $query .= " ) ";


//          // debuguear($query);

//         //  echo json_encode($query);
//         //  exit();
     

//          // Resultado de la consulta
//          $resultado = self::$db->exec($query);

//          return [
//              'resultado' => $resultado,
//              'id' => self::$db->lastInsertId(static::$tabla)
//          ];

//      } catch (\PDOException $e) {
//          // Maneja el error aquí, por ejemplo:
//          static::setAlerta('error', 'Error al crear el registro: ' . $e->getMessage());
//          return false;
//      }


//  }


 public function crear()
{
    try {
        $atributos = $this->sanitizarAtributos();

        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES (";
        $query .= join(", ", array_values($atributos));
        $query .= ")";

        $resultado = self::$db->exec($query);

        return [
            'resultado' => $resultado,
            'id' => self::$db->lastInsertId(static::$tabla)
        ];
    } catch (\PDOException $e) {
        return [
            'resultado' => false,
            'error' => $e->getMessage(),
            'codigo_sql' => $e->getCode() // aquí vamos a detectar el 1062
        ];
    }
}


    public function actualizar()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}={$value}";
        }
        $id = static::$idTabla ?? 'id';

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);

        if (is_array(static::$idTabla)) {

            foreach (static::$idTabla as $key => $value) {
                if ($value == reset(static::$idTabla)) {
                    $query .= " WHERE $value = " . self::$db->quote($this->$value);
                } else {
                    $query .= " AND $value = " . self::$db->quote($this->$value);

                }
            }
        } else {
            $query .= " WHERE " . $id . " = " . self::$db->quote($this->$id) . " ";

        }

        // debuguear($query);

        $resultado = self::$db->exec($query);
        return [
            'resultado' => $resultado,
        ];
    }

    // Eliminar un registro - Toma el ID de Active Record
    public function eliminar()
    {
        $idQuery = static::$idTabla ?? 'id';
        $query = "DELETE FROM " . static::$tabla . " WHERE $idQuery = " . self::$db->quote($this->$idQuery);
        $resultado = self::$db->exec($query);
        return $resultado;
    }

    public static function consultarSQL($query)
    {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->closeCursor();

        // retornar los resultados
        return $array;
    }

    // public static function fetchArray($query)
    // {
    //     $resultado = self::$db->query($query);
    //     $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
    //     foreach ($respuesta as $value) {
    //         $data[] = array_change_key_case(array_map('utf8_encode', $value));
    //     }
    //     $resultado->closeCursor();
    //     return $data;
    // }

    public static function fetchArray($query)
{
    $resultado = self::$db->query($query);
    $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $data = []; // Inicializa la variable $data
    foreach ($respuesta as $value) {
        $data[] = array_change_key_case($value); // Remueve utf8_encode
    }
    $resultado->closeCursor();
    return $data;
}


    public static function fetchFirst($query)
    {
        $resultado = self::$db->query($query);
        $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach ($respuesta as $value) {
            $data[] = array_change_key_case(array_map('utf8_encode', $value));
        }
        $resultado->closeCursor();
        return array_shift($data);
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            $key = strtolower($key);
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }



    // Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            $columna = strtolower($columna);
            if ($columna === 'id' || $columna === static::$idTabla)
                continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // public function sanitizarAtributos()
    // {
    //     $atributos = $this->atributos();
    //     $sanitizado = [];
    //     foreach ($atributos as $key => $value) {
    //         $sanitizado[$key] = self::$db->quote($value);
    //     }
    //     return $sanitizado;
    // }

    public function sanitizarAtributos()
{
    $atributos = $this->atributos();
    $sanitizado = [];
    foreach ($atributos as $key => $value) {
        if ($value === null) {
            // Si el valor es null, asigna NULL sin comillas
            $sanitizado[$key] = 'null';
        } else {
            // Si el valor no es null, usa quote para evitar inyecciones SQL
            $sanitizado[$key] = self::$db->quote($value);
        }
    }
    return $sanitizado;
}

    // public function sincronizar($args = [])
    // {
    //     foreach ($args as $key => $value) {
    //         if (property_exists($this, $key) && !is_null($value)) {
    //             $this->$key = $value;
    //         }
    //     }
    // }

//     public function sincronizar($args = [])
// {
//     foreach ($args as $key => $value) {
//         if (property_exists($this, $key) && $value !== null && $value !== '') {
//             $this->$key = $value; // Solo actualiza si el valor no está vacío
//         }
//     }
// }


public function sincronizar($args = [])
{
    foreach ($args as $key => $value) {
        if (property_exists($this, $key)) {
            // Si el valor es una cadena vacía, asigna null
            $this->$key = ($value === '' || $value === null) ? null : $value;
        }
    }
}


    public static function getDB() : PDO {
        return self::$db;
    }

    // // Sanitiza los atributos para incluir NULL cuando el valor sea null o cadena vacía
    // public function sanitizarAtributosConNull()
    // {
    //     $atributos = $this->atributos();
    //     $sanitizado = [];
    //     foreach ($atributos as $key => $value) {
    //         if ($value === null || $value === '') {
    //             // Si el valor es null o está vacío, asigna NULL sin comillas
    //             $sanitizado[$key] = 'null';
    //         } else {
    //             // Si el valor no es null o vacío, usa quote para evitar inyecciones SQL
    //             $sanitizado[$key] = self::$db->quote($value);
    //         }
    //     }
    //     return $sanitizado;
    // }

    public function sanitizarAtributosConNull()
{
    $atributos = $this->atributos();
    $sanitizado = [];
    foreach ($atributos as $key => $value) {
        if ($value === null  || $value === '') {
            // Si el valor es null, asigna NULL sin comillas
            $sanitizado[$key] = 'null';
        } else {
            // Usa quote para evitar inyecciones SQL
            $sanitizado[$key] = self::$db->quote($value);
        }
    }
    return $sanitizado;
}


public function actualizarConNull()
{
    // Usa la función para sanitizar atributos con manejo de NULL
    $atributos = $this->sanitizarAtributosConNull();

    // Construir los valores de la consulta
    $valores = [];
    foreach ($atributos as $key => $value) {
        $valores[] = "{$key} = {$value}";
    }

    $id = static::$idTabla ?? 'id';

    $query = "UPDATE " . static::$tabla . " SET ";
    $query .= join(', ', $valores);

    // Construcción de la cláusula WHERE
    if (is_array(static::$idTabla)) {
        foreach (static::$idTabla as $key => $value) {
            if ($value == reset(static::$idTabla)) {
                $query .= " WHERE $value = " . self::$db->quote($this->$value);
            } else {
                $query .= " AND $value = " . self::$db->quote($this->$value);
            }
        }
    } else {
        $query .= " WHERE " . $id . " = " . self::$db->quote($this->$id);
    }

    // Ejecutar la consulta
    $resultado = self::$db->exec($query);

    // Devolver el resultado
    return [
        'resultado' => $resultado,
    ];
}


    // // Método de actualización usando la nueva función de sanitización
    // public function actualizarConNull()
    // {
    //     // Usa la nueva función para sanitizar los datos
    //     $atributos = $this->sanitizarAtributosConNull();

    //     // Iterar para ir agregando cada campo de la BD
    //     $valores = [];
    //     foreach ($atributos as $key => $value) {
    //         $valores[] = "{$key}={$value}";
    //     }
    //     $id = static::$idTabla ?? 'id';

    //     $query = "UPDATE " . static::$tabla . " SET ";
    //     $query .= join(', ', $valores);

    //     if (is_array(static::$idTabla)) {
    //         foreach (static::$idTabla as $key => $value) {
    //             if ($value == reset(static::$idTabla)) {
    //                 $query .= " WHERE $value = " . self::$db->quote($this->$value);
    //             } else {
    //                 $query .= " AND $value = " . self::$db->quote($this->$value);
    //             }
    //         }
    //     } else {
    //         $query .= " WHERE " . $id . " = " . self::$db->quote($this->$id) . " ";
    //     }

    //     // Ejecutar la consulta y devolver el resultado
    //     $resultado = self::$db->exec($query);
    //     return [
    //         'resultado' => $resultado,
    //     ];
    // }

    
}
