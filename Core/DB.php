<?php

namespace Core;

use PDO;
use Core\Response;

class DB
{
    protected $dsn;
    
    protected $connection;
    
    protected $statement;
    
    protected $tables;
    
    protected $columns;

    public function __construct($config)
    {
        $this->dsn = "mysql:". http_build_query($config, "", ";");

        $this->connection = new PDO($this->dsn, $config["user"], $config["password"], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = []) 
    {
        $this->statement = $this->connection->prepare($query);
        
        $this->statement->execute($params);
        
        return $this;
    }

    public function get() 
    {
        return $this->statement->fetchAll();
    }

    public function find() 
    {
        return $this->statement->fetch();
    }

    public function findOrFail() 
    {
        if (! $item = $this->find()) {
            abort(Response::HTTP_NOT_FOUND);
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
}