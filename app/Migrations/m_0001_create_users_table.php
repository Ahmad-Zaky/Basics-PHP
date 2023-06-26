<?php

namespace App\Migrations;

use Core\MigrationManager;

class m_0001_create_users_table extends MigrationManager
{
    public function up() 
    {
        $this->execute("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            status TINYINT NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB");
    }
 
    public function down() 
    {
        $this->execute("DROP TABLE IF EXISTS users");
    }
}