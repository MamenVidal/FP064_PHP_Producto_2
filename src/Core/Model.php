<?php

namespace Core;

abstract class Model {
    
    /**
     * Datos de la base de datos
     * Los hacemos privados para que no sean accesibles desde fuera
     */
    private const DB_HOST = 'db';
    private const DB_NAME = 'phpdevelopers';
    private const DB_USER = 'phpdevelopers';
    private const DB_PASSWORD = '1234';

    /**
     * constantes a uar a lo largo de nuestra clase y que vendrán definidos con 
     * los métodos define*() en los modelos que extienden de este abstracto 
     */
    protected $pdo;
    protected $table;
    protected $pkField;
    abstract protected function defineTable(): string;
    abstract protected function definePkField(): string;

    /**
     * Constructor
     */
    public function __construct() {
        // creamos conexión a la BD mediante PDO
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->pdo = new \PDO($dsn, self::DB_USER, self::DB_PASSWORD, $options);
        // seteamos la tabla y clave primaria de nuestra entidad
        $this->table = $this->defineTable();
        $this->pkField = $this->definePkField();
    }

    /**
     * Obtenemos el registro por su id
     * @param string|int $id
     * @return array|null
     */
    public function load($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->pkField} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Guardamos un registro nuevo o existente por su id
     * @param string|int $id
     * @param array $data
     * @return array|null
     */
    public function save($id, $data) {
        if ($id === null) {
            // Si no hay $id se trata de un registro nuevo a insertar
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            
            $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            
            // Otenemos el ID del nuevo registro
            $id = $this->pdo->lastInsertId();
        } else {
            // Actualizamos el registro existente
            $updates = implode(", ", array_map(fn($col) => "{$col} = :{$col}", array_keys($data)));
            $sql = "UPDATE {$this->table} SET {$updates} WHERE {$this->pkField} = :pk";
            $data['pk'] = $id;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        }
        // retornamos el registr creado/actualizado por su id
        return $this->load($id);
    }

    /**
     * Recuperamos todos los registros de la entidad
     * @return array|null
     */
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Eliminamos un registro por su id
     * @param string|int $id
     * @return bool
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->pkField} = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Ejecuta una consulta SQL genérica con parámetros opcionales y devuelve el resultado.
     *
     * @param string $sql La consulta SQL a ejecutar.
     * @param array $params Parámetros para la consulta SQL.
     * @return array|bool El resultado de la consulta. Puede ser un array de datos o un booleano para las operaciones que no devuelven datos.
     */
    public function query($sql, array $params = []) {
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute($params)) {
            if (preg_match('/^(SELECT|SHOW|DESCRIBE|EXPLAIN)\s/i', $sql)) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                // Para consultas que no devuelven datos (INSERT, UPDATE, DELETE), devolver el estado de la operación
                return true;
            }
        }
        
        return false;
    }
}
