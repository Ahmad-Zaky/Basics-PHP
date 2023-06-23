<?php

namespace App\Migrations;

use Core\Migration;

class m_0003_add_completed_column_to_notes_table extends Migration
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