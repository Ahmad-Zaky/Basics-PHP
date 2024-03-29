<?php

namespace App\Migrations;

use Core\MigrationManager;

class m_2023_11_13_084926_create_notes_table extends MigrationManager
{
    public function up() 
    {
        $this->execute("CREATE TABLE IF NOT EXISTS notes (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
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
        $this->execute("DROP TABLE IF EXISTS notes");
    }
}