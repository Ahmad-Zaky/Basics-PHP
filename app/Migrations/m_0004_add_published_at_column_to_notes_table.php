<?php

namespace App\Migrations;

use Core\Migration;

class m_0004_add_published_at_column_to_notes_table extends Migration
{
    public function up() 
    {
        $this->execute("ALTER TABLE notes ADD published_at TIMESTAMP NULL AFTER published");        
    }
 
    public function down() 
    {
        $this->execute("ALTER TABLE notes DROP COLUMN published_at");
    }
}