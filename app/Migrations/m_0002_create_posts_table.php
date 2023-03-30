<?php

namespace App\Migrations;

use Core\Migration;

class m_0002_create_posts_table extends Migration
{
    public function up() 
    {
        $this->execute("CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            body VARCHAR(255) NOT NULL,
            user_id INT NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id)
            REFERENCES users (id) 
            ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=INNODB");
    }
 
    public function down() 
    {
        $this->execute("DROP TABLE IF EXISTS posts");
    }
}