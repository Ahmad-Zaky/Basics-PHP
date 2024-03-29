<?php

namespace Core\Contracts;

use PDO;

interface DB
{
    public static function getInstance(array $config): self;

    public function getConnection(): PDO;    

    public function raw(string $query): self;

    public function query(string $query, array $params = []): self;

    public function get(int $options = PDO::FETCH_DEFAULT): array;

    public function find(int $options = PDO::FETCH_DEFAULT): mixed;

    public function findOrFail(int $options = PDO::FETCH_DEFAULT): mixed;

    public function tables(): array;

    public function tableExists(string $table): bool;

    public function columns(string $table): array;

    public function columnExists(string $table, string $column): bool;
}