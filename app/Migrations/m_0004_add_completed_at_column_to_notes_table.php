<?php

namespace App\Migrations;

use Core\Migration;

class m_0004_add_completed_at_column_to_notes_table extends Migration
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