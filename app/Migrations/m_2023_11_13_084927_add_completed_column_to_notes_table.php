<?php

namespace App\Migrations;

use Core\MigrationManager;

class m_2023_11_13_084927_add_completed_column_to_notes_table extends MigrationManager
{
    public function up() 
    {
        $this->execute("ALTER TABLE notes ADD completed TINYINT NOT NULL DEFAULT 0 AFTER body");        
    }
 
    public function down() 
    {
        $this->execute("ALTER TABLE notes DROP COLUMN completed");
    }
}