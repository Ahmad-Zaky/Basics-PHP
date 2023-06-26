<?php

namespace Core\Contracts;

interface Migration
{
    public const BLACK_COLOR = "\033[01;30m";
    public const RED_COLOR = "\033[01;31m";
    public const GREEN_COLOR = "\033[01;32m";
    public const YELLOW_COLOR = "\033[01;33m";
    public const BLUE_COLOR = "\033[01;34m";
    public const MAGENTA_COLOR = "\033[01;35m";
    public const CYAN_COLOR = "\033[01;36m";
    public const LIGHT_GRAY_COLOR = "\033[01;37m";
    public const DEFAULT_COLOR = "\033[01;39m";
    public const DARK_GRAY_COLOR = "\033[01;90m";
    public const LIGHT_RED_COLOR = "\033[01;91m";
    public const LIGHT_GREEN_COLOR = "\033[01;92m";
    public const LIGHT_YELLOW_COLOR = "\033[01;93m";
    public const LIGHT_BLUE_COLOR = "\033[01;94m";
    public const LIGHT_MAGENTA_COLOR = "\033[01;95m";
    public const LIGHT_CYAN_COLOR = "\033[01;96m";
    public const WHITE_COLOR = "\033[01;97m";

    public function migrate(): void;
    
    public function createMigrationsTable(): void;
    
    public function getMigrations(): array;
   
    public function addCreatedMigrations(array $migrations): void;
 
    public function isFreshAction(): bool;
  
    public function isDownAction(): bool;

    public function cleanMigrations(): void;
  
    public function dropAllTables(): void;

    public function tables(): array;
   
    public function log(string $message, string $color = self::GREEN_COLOR, bool $withDate = true): void;

    public function execute(string $query): void;
}