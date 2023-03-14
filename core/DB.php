<?php


class DB
{

    protected $dsn;
    
    protected $connection;
    
    public function __construct($config)
    {
        $this->dsn = "mysql:". http_build_query($config, "", ";");

        $this->connection = new PDO($this->dsn, $config["username"], $config["password"], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = []) 
    {
        $statement = $this->connection->prepare($query);
        
        $statement->execute($params);
        
        return $statement;
    }
}

$db = new DB($config["db"]);

// Testing
// $posts = $db->query("SELECT * FROM posts")->fetchAll();
// dd($posts);