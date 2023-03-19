<?php

namespace Core;

class Model
{
    protected static $table = "";

    public static function calledClass() 
    {
        return get_called_class();
    }

    public static function getTable() 
    {
        return (new (self::calledClass()))->table;
    }

    public static function all() 
    {
        $db = app(DB::class);
        $table = self::getTable();

        return $db->query("SELECT * FROM {$table}")->get();
    }

    public static function find(int $id)
    {
        $db = app(DB::class);
        $table = self::getTable();

        return $db->query("SELECT * FROM {$table} WHERE id = :id", ["id" => $id])->find();
    }

    public static function findOrFail(int $id)
    {
        $db = app(DB::class);
        $table = self::getTable();

        return $db->query("SELECT * FROM {$table} WHERE id = :id", ["id" => $id])->findOrFail();
    }

    public static function create(array $data) 
    {
        $db = app(DB::class);
        
        $table = self::getTable();
        $fields = implode(', ', array_keys($data));
        $placeHolders = implode(', ', array_fill(0, count($data), "?"));

        return $db->query("INSERT INTO {$table} ({$fields}) VALUES ({$placeHolders})", array_values($data));
    }

    public static function update(int $id, array $data) 
    {
        $db = app(DB::class);
        $table = self::getTable();

        $fields = ""; foreach (array_keys($data) as $field) {
            $fields .= "{$field} = :{$field}, ";
        }

        $fields = rtrim($fields, ", ");

        return $db->query("UPDATE {$table} SET {$fields} WHERE id = :id", array_merge($data, ["id" => $id]));
    }

    public static function delete(int $id) 
    {
        $db = app(DB::class);
        $table = self::getTable();

        return $db->query("DELETE FROM {$table} WHERE id = :id", ["id" => $id]);
    }
}