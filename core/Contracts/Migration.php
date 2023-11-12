<?php

namespace Core\Contracts;

interface Migration
{
    public function migrate(): void;
    
    public function createMigrationsTable(): void;
    
    public function getMigrations(): array;
   
    public function addCreatedMigrations(array $migrations): void;
 
    public function isFreshAction(): bool;
  
    public function isDownAction(): bool;

    public function cleanMigrations(): void;
  
    public function dropAllTables(): void;

    public function tables(): array;

    public function execute(string $query): void;
}