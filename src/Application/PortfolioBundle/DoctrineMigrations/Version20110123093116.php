<?php

namespace PortfolioBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20110123093116 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->_addSql("CREATE TABLE portfolio_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
    }

    public function down(Schema $schema)
    {
        $this->_addSql("DROP TABLE portfolio_categories");
    }
}