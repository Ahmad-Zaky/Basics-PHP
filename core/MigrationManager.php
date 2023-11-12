<?php

namespace Core;

use Core\Contracts\DB;
use Core\Contracts\Migration;
use Core\CommandColors;
use PDO;

class MigrationManager implements Migration
{
    public array $migrations = [];

    function __construct(protected ?DB $db = null)
    {
        if (! isset($this->db)) $this->db = app(DB::class); 
    }

    public function migrate(): void
    {
        if ($this->isFreshAction()){
            $this->cleanMigrations();
            $this->dropAllTables();
        }
        
        $this->createMigrationsTable();
        $this->getMigrations();

        $this->isDownAction() ? $this->downMigration() : $this->upMigration();
    }

    // TODO: should be refactored move db logic to db class
    public function createMigrationsTable(): void
    {
        $this->db
            ->raw(
                'CREATE TABLE IF NOT EXISTS migrations (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    migration VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=INNODB;'
            );
    }

    public function getMigrations(): array
    {
        return $this->migrations = $this->db
            ->query('SELECT migration FROM migrations')
            ->get(PDO::FETCH_COLUMN);
    }

    // TODO: should be refactored move db logic to db class
    public function addCreatedMigrations(array $migrations): void 
    {
        $query = implode(', ', array_map(fn($m) => "('{$m}')", $migrations));

        $this->db->query("INSERT INTO migrations (migration) VALUES $query");
    }
    
    public function isFreshAction(): bool
    {        
        global $argv;

        return ($argv[1] ?? "") === "fresh";
    }
    
    public function isDownAction(): bool
    {        
        global $argv;

        return ($argv[1] ?? "") === "down";
    }

    public function cleanMigrations(): void
    {
        $this->db->query("DELETE FROM migrations");
    }

    public function dropAllTables(): void
    {
        logging("Dropping tables ...");

        $this->db->raw("SET FOREIGN_KEY_CHECKS = 0");

        $tables = $this->tables();
        foreach ($tables as $table) {
            if ($table === "migrations") continue;

            $this->db->raw("DROP TABLE IF EXISTS {$table};");
        }

        $this->db->raw("SET FOREIGN_KEY_CHECKS = 1");
    }

    public function tables(): array
    {
        $dbName = config("database.connection.dbname") ?? NULL;

        return $this->db->query(
            "SELECT
                table_name
            FROM
                information_schema.tables
            WHERE
                table_schema = ?",
            [$dbName]
        )->get(PDO::FETCH_COLUMN);
    }

    protected function upMigration(): void
    {
        $files = scandir(migrationsPath());
        $notMigrated = array_diff($files, $this->migrations);

        $createdMigrations = [];
        foreach ($notMigrated as $migration) {
            if (in_array($migration, [".", ".."])) continue;

            require migrationsPath() . $migration;

            $file = pathinfo($migration, PATHINFO_FILENAME);
            $class = '\App\Migrations\\'.$file;
            
            logging("Creating migration {$file}");

            (new $class)->up();
            
            logging("Created migration {$file}");

            $createdMigrations[] = $migration;
        }

        if (empty($createdMigrations)) {
            logging("No migrations to migrate", CommandColors::RED_COLOR, false);
            return;
        }
    
        $this->addCreatedMigrations($createdMigrations);
    }

    protected function downMigration(): void
    {
        $this->cleanMigrations();

        $migrations = array_reverse(scandir(migrationsPath()));

        foreach ($migrations as $migration) {
            if (in_array($migration, [".", ".."])) continue;

            require migrationsPath() . $migration;

            $file = pathinfo($migration, PATHINFO_FILENAME);
            $class = '\App\Migrations\\'.$file;

            logging("Down migration {$file} Started");

            $this->db->raw("SET FOREIGN_KEY_CHECKS = 0");

            (new $class)->down();

            $this->db->raw("SET FOREIGN_KEY_CHECKS = 1");

            logging("Down migration {$file} Ended");

            $createdMigrations[] = $migration;
        }
    }

    public function execute(string $query): void
    {
        if ($query) $this->db->raw($query);
    }
}