<?php

namespace App\Migrations;

use Core\Migration;

class m_0003_add_published_column_to_notes_table extends Migration
{
    public function up() 
    {
        $this->execute("ALTER TABLE notes ADD published TINYINT NOT NULL DEFAULT 0 AFTER body");        
    }
 
    public function down() 
    {
        $this->execute("ALTER TABLE notes DROP COLUMN published");
    }
}