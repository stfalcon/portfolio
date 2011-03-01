<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20110301020914 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE portfolio_projects ADD date VARCHAR(255) DEFAULT NULL, CHANGE image image VARCHAR(255) NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE portfolio_projects DROP date, CHANGE image image VARCHAR(255) DEFAULT NULL");
    }
}