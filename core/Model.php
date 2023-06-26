<?php

namespace Core;

use Core\Contracts\{DB, ModelContract};
use Core\Exceptions\ModelNotFoundException;

class Model implements ModelContract
{
    protected string $table = "";
    
    protected array $attributes = [];
    
    protected $query = NULL;

    public static function calledClass(): string
    {
        return get_called_class();
    }

    public static function calledClassInstance(): self
    {
        return new (self::calledClass());
    }

    public static function getTable(): string
    {
        return self::calledClassInstance()->table;
    }

    protected function getAttribute($key): string|NULL
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return NULL;
    }

    protected function setAttribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function toArray(): array
    {
        return $this->getAttributes();
    }

    public function setAttributes(array $data): self
    {
        foreach($data as  $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->setAttribute($key, $value);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    protected function buildModels(array $modelsList): array
    {
        $models = [];
        foreach ($modelsList as $modelArr) {
            $models[] = $this->buildModel($modelArr);
        }

        return $models;
    }

    protected function buildModel(array $data, bool $new = true): self
    {
        $model = $new ? self::calledClassInstance() : $this;

        $model->setAttributes($data);

        return $model;
    }

    public static function all(): array
    {
        $db = app(DB::class);
        $table = self::getTable();

        return self::calledClassInstance()->buildModels($db->query("SELECT * FROM {$table}")->get());
    }

    public static function find(int $id): mixed
    {
        $db = app(DB::class);
        $table = self::getTable();

        $found = $db->query("SELECT * FROM {$table} WHERE id = :id", ["id" => $id])->find();

        return $found ? self::calledClassInstance()->buildModel($found) : NULL;
    }

    public static function findOrFail(int $id): mixed
    {
        $db = app(DB::class);
        $table = self::getTable();

        return self::calledClassInstance()->buildModel($db->query("SELECT * FROM {$table} WHERE id = :id", ["id" => $id])->findOrFail());
    }

    public static function create(array $data): mixed
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

    public function update(array $data): mixed
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

    public function delete(): mixed
    {
        $db = app(DB::class);
        $table = self::getTable();

        if ($db->query("DELETE FROM {$table} WHERE id = :id", ["id" => $this->id])) {
            return $this;
        }

        return NULL;
    }

    public function get(): array
    {
        return $this->buildModels($this->query->get());
    }

    public function first(): self|NULL
    {
        return $this->buildModels($this->query->get())[0] ?? NULL;
    }

    public function firstOrFail(): mixed
    {
        return $this->buildModels($this->query->get())[0] ?? throw new ModelNotFoundException;
    }

    public function query(mixed $statement, array $params): self
    {
        $db = app(DB::class);

        $this->query = $db->query($statement, $params);

        return $this; 
    }
}