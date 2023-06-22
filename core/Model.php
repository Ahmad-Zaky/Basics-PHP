<?php

namespace Core;

use Core\Exceptions\ModelNotFoundException;

class Model
{
    protected $table = "";
    
    protected $attributes = [];
    
    protected $query = NULL;

    public static function calledClass() 
    {
        return get_called_class();
    }

    public static function calledClassInstance() 
    {
        return new (self::calledClass());
    }

    public static function getTable() 
    {
        return self::calledClassInstance()->table;
    }
    
    protected function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return NULL;
    }

    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function toArray()
    {
        return $this->getAttributes();
    }

    public function setAttributes($data) 
    {
        foreach($data as  $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function has($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    protected function buildModels($modelsList)
    {
        $models = [];
        foreach ($modelsList as $modelArr) {
            $models[] = $this->buildModel($modelArr);
        }

        return $models;
    }

    protected function buildModel($data, $new = true)
    {
        $model = $new ? self::calledClassInstance() : $this;

        $model->setAttributes($data);

        return $model;
    }

    public static function all() 
    {
        $db = app(DB::class);
        $table = self::getTable();

        return self::calledClassInstance()->buildModels($db->query("SELECT * FROM {$table}")->get());
    }

    public static function find(int $id)
    {
        $db = app(DB::class);
        $table = self::getTable();

        return self::calledClassInstance()->buildModel($db->query("SELECT * FROM {$table} WHERE id = :id", ["id" => $id])->find());
    }

    public static function findOrFail(int $id)
    {
        $db = app(DB::class);
        $table = self::getTable();

        return self::calledClassInstance()->buildModel($db->query("SELECT * FROM {$table} WHERE id = :id", ["id" => $id])->findOrFail());
    }

    public static function create(array $data) 
    {
        $db = app(DB::class);
        
        $table = self::getTable();
        $fields = implode(', ', array_keys($data));
        $placeHolders = implode(', ', array_fill(0, count($data), "?"));

        if ($db->query("INSERT INTO {$table} ({$fields}) VALUES ({$placeHolders})", array_values($data))) {
            return self::calledClassInstance()->buildModel(array_merge($data, ["id" => $db->lastId()]));
        }
        
        return NULL;
    }

    public function update(array $data) 
    {
        $db = app(DB::class);
        $table = self::getTable();

        $fields = ""; foreach (array_keys($data) as $field) {
            $fields .= "{$field} = :{$field}, ";
        }

        $fields = rtrim($fields, ", ");

        if ($db->query("UPDATE {$table} SET {$fields} WHERE id = :id", array_merge($data, ["id" => $this->id]))) {
            return $this->buildModel($data, false);
        }

        return NULL;
    }

    public function delete() 
    {
        $db = app(DB::class);
        $table = self::getTable();

        if ($db->query("DELETE FROM {$table} WHERE id = :id", ["id" => $this->id])) {
            return $this;
        }

        return NULL;
    }

    public function get()
    {
        return $this->buildModels($this->query->get());
    }

    public function first()
    {
        return $this->buildModels($this->query->get())[0] ?? NULL;
    }

    public function firstOrFail()
    {
        return $this->buildModels($this->query->get())[0] ?? throw new ModelNotFoundException;
    }

    public function query($statement, $params)
    {
        $db = app(DB::class);

        $this->query = $db->query($statement, $params);

        return $this; 
    }
}