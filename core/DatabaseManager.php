<?php

namespace Core;

use Core\Contracts\DB;
use Core\Contracts\Response;
use Core\Exceptions\ModelNotFoundException;
use Exception;
use PDO;
use PDOException;

class DatabaseManager implements DB
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

    public static function getInstance(array $config): self
    {
        if (! self::$instance) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }
    
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function raw(string $query): self
    {
        $this->connection->exec($query);
        
        return $this;
    }

    public function query(string $query, array $params = []): self
    {
        $this->statement = $this->connection->prepare($query);
        
        $this->statement->execute($params);
        
        return $this;
    }

    public function get(int $options = PDO::FETCH_DEFAULT): array
    {
        return $this->statement->fetchAll($options);
    }

    public function find(int $options = PDO::FETCH_DEFAULT): mixed
    {
        return $this->statement->fetch($options);
    }

    public function findOrFail(int $options = PDO::FETCH_DEFAULT): mixed
    {
        if (! $item = $this->find($options)) {
            throw new ModelNotFoundException();
        }

        return $item;
    }

    public function tables(): array
    {
        foreach ($this->query("SHOW TABLES")->get() as $table) {
            $this->tables[array_values($table)[0]] = $table;
        }
        
        return $this->tables;
    }

    public function tableExists(string $table): bool
    {
        if (empty($this->tables)) $this->tables();

        return isset($this->tables[$table]);
    }

    public function columns(string $table): array
    {
        foreach ($this->query("DESCRIBE {$table};")->get() as $column) {
            $this->columns[$table][$column["Field"]] = $column;
        }
        
        return $this->columns;
    }

    public function columnExists(string $table, string $column): bool
    {
        if (empty(isset($this->columns[$table]))) $this->columns($table);

        return isset($this->columns[$table][$column]);
    }

    public function lastId(): mixed
    {
        return $this->connection->lastInsertId();
    }

    final public function __clone() {
        throw new Exception(__("Can't clone a singleton"));
    }

    final public function __wakeup()
    {
        throw new Exception(__("Can't clone a singleton"));
    }

    final public function __destruct()
    {
        $this->connection = null;
        self::$instance = null;
    }
}