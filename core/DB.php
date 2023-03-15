<?php

class DB
{
    protected $dsn;
    
    protected $connection;
    
    protected $statement;

    public function __construct($config)
    {
        $this->dsn = "mysql:". http_build_query($config, "", ";");

        $this->connection = new PDO($this->dsn, $config["username"], $config["password"], [
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
}

$db = new DB($config["db"]);
