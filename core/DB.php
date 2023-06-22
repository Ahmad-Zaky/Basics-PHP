<?php

namespace Core;

use Core\Exceptions\ModelNotFoundException;
use Exception;
use PDO;
use PDOException;

class DB
{
    private static $instance = null;

    protected $dsn;
    
    protected $connection;
    
    protected $statement;
    
    protected $tables;
    
    protected $columns;

    private function __construct(array $config)
    {
        try {
            $this->dsn = "mysql:". http_build_query($config, "", ";");
        
            $this->connection = new PDO($this->dsn, $config["user"], $config["password"], [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        } catch (Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public static function getInstance(array $config)
    {
        if (! self::$instance) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }

    public function raw($query)
    {
        $this->connection->exec($query);
        
        return $this;
    }

    public function query($query, $params = []) 
    {
        $this->statement = $this->connection->prepare($query);
        
        $this->statement->execute($params);
        
        return $this;
    }

    public function get($options = PDO::FETCH_DEFAULT) 
    {
        return $this->statement->fetchAll($options);
    }

    public function find($options = PDO::FETCH_DEFAULT) 
    {
        return $this->statement->fetch($options);
    }

    public function findOrFail($options = PDO::FETCH_DEFAULT) 
    {
        if (! $item = $this->find($options)) {
            throw new ModelNotFoundException();
        }

        return $item;
    }

    public function tables() 
    {
        foreach ($this->query("SHOW TABLES")->get() as $table) {
            $this->tables[array_values($table)[0]] = $table;
        }
        
        return $this->tables;
    }

    public function tableExists($table)
    {
        if (empty($this->tables)) $this->tables();

        return isset($this->tables[$table]);
    }

    public function columns($table) 
    {
        foreach ($this->query("DESCRIBE {$table};")->get() as $column) {
            $this->columns[$table][$column["Field"]] = $column;
        }
        
        return $this->columns;
    }

    public function columnExists($table, $column)
    {
        if (empty(isset($this->columns[$table]))) $this->columns($table);

        return isset($this->columns[$table][$column]);
    }

    public function lastId()
    {
        return $this->connection->lastInsertId();
    }

    final public function __clone() {
        throw new Exception("Can't clone a singleton");
    }

    final public function __wakeup()
    {
        throw new Exception("Can't clone a singleton");
    }

    final public function __destruct()
    {
        $this->connection = null;
        self::$instance = null;
    }
}