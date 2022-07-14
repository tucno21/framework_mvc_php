<?php

/**
 * ActiveRecord para extender en los modelos
 */

namespace System;

use System\ConnectDB;


class Model
{
    /**
     * conexion a la base de datos
     */
    protected static $db;
    /**
     * nombre de la tabla
     */
    protected static $table = '';
    /**
     * nombre de la columnas de la tabla
     */
    protected static $allowedFields = [];

    /**
     * obtener los datos de la tabla en 'array' u 'object'
     */
    protected static $returnType     = 'array';
    /**
     * constructor de la clase
     */
    protected static $where = null;
    protected static $orderBy = null;
    protected static $select = null;
    protected static $join = null;

    /**
     * si existe columna password para encriptar
     */
    protected static $passEncrypt = false;
    protected static $password =  null;

    /**
     * registro de tiempos de creacion y/o actualizacion
     */
    protected static $useTimestamps   = false;
    protected static $createdField    = null;
    protected static $updatedField    = null;

    /**
     * query para MySQL
     */
    protected static $query;

    /**
     * constructor de la clase
     */
    public function __construct()
    {
        $database = new ConnectDB();
        self::$db = $database->conn;
    }

    /**
     * obtener la conexion a la base de datos
     */

    private static function getConnection()
    {
        //instanciar toda la clase para invocar al constructor
        return new static;
    }


    /**
     * obtener la coneccion a la base de datos
     */
    // public static function get_db($database)
    // {
    //     self::$db = $database;
    // }

    /**
     * cerrar la coneccion a la base de datos
     */
    private static function db_close()
    {
        self::$db->close();
    }

    /**
     * relaciona el array  que biene del controlador con la tabla del modelo creado
     * no toma en cuenta los imputs que no existan en la tabla
     */
    protected static function allowedFields(array $data)
    {
        $fields = [];
        foreach (static::$allowedFields as $val) {
            if (isset($data[$val])) {
                $fields[$val] = $data[$val];
            }
        }
        // Limpia de codigo dañino
        $data = self::sanitize($fields);
        return $data;
    }

    /**
     * Escapa los caracteres especiales de una cadena para usarla en una sentencia SQL
     */
    protected static function sanitize(array $data)
    {
        $sanitize = [];
        foreach ($data as $key => $value) {
            $sanitize[$key] = self::$db->escape_string($value);
        }
        return $sanitize;
    }

    /**
     * ejecutar query de CREATE, UPDATE, DELETE
     */
    protected static function runQuery()
    {
        self::getConnection();
        try {
            $result = self::$db->query(self::$query);

            $status = $result;
            $affected_rows = self::$db->affected_rows;
            $id = self::$db->insert_id;

            self::db_close();

            if ($id != 0) {
                return [
                    'result' =>  $status,
                    'id' => $id
                ];
            }

            if ($affected_rows != 0) {
                return [
                    'result' =>  true
                ];
            } else {
                return [
                    'result' =>  false
                ];
            }
        } catch (Exception $e) {

            return $e->getMessage();
            // self::db_close();
        }
    }

    /**
     * crear un nuevo registro
     */
    public static function create(array $data)
    {
        self::passEncrypt();
        self::useTimestamps();

        $send = self::allowedFields($data);

        if (self::$passEncrypt === true) {
            $send['password'] = password_hash($send['password'], PASSWORD_BCRYPT);
        }

        if (self::$useTimestamps == true) {
            $send[static::$createdField] = date('Y-m-d H:i:s');
        }

        $columns = implode(", ", array_keys($send));
        $values = implode("', '", array_values($send));

        self::$query = "INSERT INTO " . static::$table . "($columns) VALUES ('$values')";

        $result = self::runQuery();

        return $result;
    }

    /**
     * actualizar un registro
     */
    public static function update(mixed $id, array $data)
    {
        self::passEncrypt();
        self::useTimestamps();

        $send = self::allowedFields($data);

        if (self::$useTimestamps == true) {
            $send[static::$updatedField] = date('Y-m-d H:i:s');
        }

        if (self::$passEncrypt === true) {
            $send['password'] = password_hash($send['password'], PASSWORD_BCRYPT);
        }

        $cv = [];
        foreach ($send as $key => $value) {
            $cv[] = "`{$key}`='{$value}'";
        }
        $primaryKey = static::$primaryKey;

        $columValue = join(', ', $cv);
        self::$query = "UPDATE " . static::$table . " SET $columValue WHERE $primaryKey= '" . self::$db->escape_string($id) . "'";

        $result = self::runQuery();

        return $result;
    }

    /**
     * eliminar un registro
     */
    public static function delete(mixed $id)
    {
        $primaryKey = static::$primaryKey;

        self::$query = "DELETE FROM " . static::$table . " WHERE $primaryKey= '" . self::$db->escape_string($id) . "'";

        $result = self::runQuery();

        return $result;
    }

    public static function destroy(mixed $id)
    {
        $primaryKey = static::$primaryKey;

        self::$query = "DELETE FROM " . static::$table . " WHERE $primaryKey= '" . self::$db->escape_string($id) . "'";

        $result = self::runQuery();

        return $result;
    }


    /**
     * recibe el query y retorna los resultados
     */
    private static function readDB()
    {
        self::getConnection();
        self::returnType();

        //ejecuta el query y retorna los resultados
        $result = self::$db->query(self::$query);

        //alamcenar los resultados en un array
        $dataQuery = [];
        if (self::$returnType == 'object') {
            while ($row = $result->fetch_object()) {
                $dataQuery[] = $row;
            }
        } elseif (self::$returnType == 'array') {
            while ($row = $result->fetch_assoc()) {
                $dataQuery[] = $row;
            }
        }
        // $result->free_result();
        // $result->free();
        $result->close();
        // self::db_close();

        return  $dataQuery;
    }

    /**
     * obtener todos los resultados
     */
    public static function all()
    {
        self::$query = "SELECT * FROM " . static::$table;
        return self::readDB();
    }

    /**
     * obtener un solo resultado
     */
    public static function find(mixed $id)
    {
        $id = self::$db->escape_string($id);
        self::$query = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = '$id'";
        return self::readDB();
    }

    /**
     * buscar una fila por su (id o nombre de columna, valor de columna)
     */
    public static function first(mixed $colum = null, mixed $valColum = null)
    {
        if ($colum != null || $valColum != null) {
            $colum = self::$db->escape_string($colum);
            $valColum = self::$db->escape_string((string) $valColum);
        }

        if ($colum != null && $valColum != null) {
            self::$query = "SELECT * FROM " . static::$table . " WHERE $colum = '" . $valColum . "'";
        } elseif ($colum != null) {
            $primaryKey = static::$primaryKey;
            self::$query = "SELECT * FROM " . static::$table . " WHERE $primaryKey = '$colum'";
        } else {
            self::$query = "SELECT * FROM " . static::$table . self::$where . self::$orderBy . " LIMIT 1";
        }

        return self::readDB();
        // return self::$query;
    }

    /**
     * traer todos los resultados de una query con la combinacion de otras funciones
     */
    public static function get()
    {
        if (self::$select != null) {
            self::$query = "SELECT " . self::$select . " FROM " . static::$table . self::$join . self::$where . self::$orderBy;
        } else {
            self::$query = "SELECT * FROM " . static::$table . self::$where . self::$orderBy;
        }

        return self::readDB();
        // return self::$query;
    }



    /**
     * posibles combinaciones con get(), first();
     */

    /**
     * busqueda con condicion where
     */
    public static function where(string $colum, string $operator = null, mixed $valueColum = null)
    {
        $colum = self::$db->escape_string($colum);
        $operator = self::$db->escape_string((string) $operator);
        $valueColum = self::$db->escape_string((string) $valueColum);


        if ($operator != null && $valueColum != null) {
            self::$where = " WHERE $colum $operator '$valueColum'";
        } else {
            self::$where = " WHERE $colum = '$operator'";
        }

        return new static;
    }

    /**
     * ordenar los resultados
     * en funcion al campo y el orden
     */
    public static function orderBy(string $colum, string $order)
    {
        $colum = self::$db->escape_string($colum);
        $order = self::$db->escape_string((string) $order);

        self::$orderBy = " ORDER BY $colum " . strtoupper($order);
        return new static;
    }

    /**
     * obtener una cantidad de resultados que se tiene de la consulta
     */
    public static function count()
    {
        self::$query = "SELECT * FROM " . static::$table . self::$where . self::$orderBy;

        $count = self::readDB();
        return count($count);
    }

    /**
     * obtener una cantidad MAX de la columna
     */
    public static function max(string $colum)
    {
        $colum = self::$db->escape_string($colum);
        self::$query = "SELECT MAX($colum) FROM " . static::$table . self::$where . self::$orderBy;
        $max = self::readDB();

        if (self::$returnType == 'object') {
            foreach ($max as $m => $v) {
                foreach ($v as $key => $value) {
                    return $value;
                }
            }
        } elseif (self::$returnType == 'array') {
            return $max[0]["MAX($colum)"];
        }
    }

    /**
     * obtener una cantidad MIN de la columna
     */
    public static function min(string $colum)
    {
        $colum = self::$db->escape_string($colum);
        self::$query = "SELECT MIN($colum) FROM " . static::$table . self::$where . self::$orderBy;
        $min = self::readDB();

        if (self::$returnType == 'object') {
            foreach ($min as $m => $v) {
                foreach ($v as $key => $value) {
                    return $value;
                }
            }
        } elseif (self::$returnType == 'array') {
            return $min[0]["MIN($colum)"];
        }
    }

    /**
     * obtener el promedio de la columna
     */
    public static function avg(string $colum)
    {
        $colum = self::$db->escape_string($colum);
        self::$query = "SELECT AVG($colum) FROM " . static::$table . self::$where . self::$orderBy;
        $avg = self::readDB();

        if (self::$returnType == 'object') {
            foreach ($avg as $m => $v) {
                foreach ($v as $key => $value) {
                    return $value;
                }
            }
        } elseif (self::$returnType == 'array') {
            return $avg[0]["AVG($colum)"];
        }
    }

    /**
     * obtener una cantidad SUM de la columna
     */
    public static function sum(string $colum)
    {
        $colum = self::$db->escape_string($colum);
        self::$query = "SELECT SUM($colum) FROM " . static::$table . self::$where . self::$orderBy;
        $sum = self::readDB();

        if (self::$returnType == 'object') {
            foreach ($sum as $m => $v) {
                foreach ($v as $key => $value) {
                    return $value;
                }
            }
        } elseif (self::$returnType == 'array') {
            return $sum[0]["SUM($colum)"];
        }
    }

    /**
     * recuperar datos de varias tablas al mismo tiempo.
     */
    public static function join($table, $colum, $operator, $colum2)
    {
        self::$join =  " INNER JOIN $table ON $colum $operator $colum2";
        return new static;
    }

    /**
     * selecionar las columnas que se desea buscar
     */
    public static function select($colum_a = null, $colum_b = null, $colum_c = null, $colum_d = null, $colum_e = null, $colum_f = null, $colum_g = null)
    {
        if ($colum_a != null) {

            $colum_b = is_null($colum_b) ? "" : ", $colum_b";
            $colum_c = is_null($colum_c) ? "" : ", $colum_c";
            $colum_d = is_null($colum_d) ? "" : ", $colum_d";
            $colum_e = is_null($colum_e) ? "" : ", $colum_e";
            $colum_f = is_null($colum_f) ? "" : ", $colum_f";
            $colum_g = is_null($colum_g) ? "" : ", $colum_g";

            self::$select = "$colum_a$colum_b$colum_c$colum_d$colum_e$colum_f$colum_g";
        }
        return new static;
    }


    /**
     * funciones especiales para la consulta
     */

    /**
     * encriptar la columna contraseña
     */
    private static function passEncrypt()
    {
        if (property_exists(static::class, 'passEncrypt')) {
            self::$passEncrypt = static::$passEncrypt;
            self::$password = static::$password;
        }
    }

    /**
     * verifica si existe los columanas useTimestamps
     */
    private static function useTimestamps()
    {
        if (property_exists(static::class, 'useTimestamps')) {
            self::$useTimestamps = static::$useTimestamps;
        }

        if (property_exists(static::class, 'createdField')) {
            self::$createdField = static::$createdField;
        }

        if (property_exists(static::class, 'updatedField')) {
            self::$updatedField = static::$updatedField;
        }
    }

    /**
     * verificar si existe returnType en el modelo hijo y pasar el valor a la variable
     * del modelo padre
     */
    private static function returnType()
    {
        if (property_exists(static::class, 'returnType')) {
            self::$returnType = static::$returnType;
        }
    }

    /**
     * JQUERY PERSONALIZADO
     */

    //RECIVE UN QUERY Y ENVIA GRUPOS DE OBJETOS
    public static function queryMod(string $query)
    {
        // self::getConnection();
        self::$query = $query;
        return self::readDB();
    }
}
