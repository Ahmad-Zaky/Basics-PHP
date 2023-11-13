<?php

namespace App\Migrations;

use Core\MigrationManager;

class m_2023_11_13_084928_add_completed_at_column_to_notes_table extends MigrationManager
{
    public function up() 
    {
        $this->execute("ALTER TABLE notes ADD completed_at TIMESTAMP NULL AFTER completed");        
    }
 
    public function down() 
    {
        $this->execute("ALTER TABLE notes DROP COLUMN completed_at");
    }
}