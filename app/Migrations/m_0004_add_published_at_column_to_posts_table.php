<?php

namespace App\Migrations;

use Core\Migration;

class m_0004_add_published_at_column_to_posts_table extends Migration
{
    public function up() 
    {
        $this->execute("ALTER TABLE posts ADD published_at TIMESTAMP NULL AFTER published");        
    }
 
    public function down() 
    {
        $this->execute("ALTER TABLE posts DROP COLUMN published_at");
    }
}