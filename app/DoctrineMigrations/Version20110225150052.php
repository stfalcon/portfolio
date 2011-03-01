<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20110225150052 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE portfolio_projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE portfolio_projects_categories (project_id INT NOT NULL, category_id INT NOT NULL, INDEX portfolio_projects_categories_project_id_idx (project_id), INDEX portfolio_projects_categories_category_id_idx (category_id), PRIMARY KEY(project_id, category_id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE portfolio_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE portfolio_projects_categories ADD FOREIGN KEY (project_id) REFERENCES portfolio_projects(id)");
        $this->addSql("ALTER TABLE portfolio_projects_categories ADD FOREIGN KEY (category_id) REFERENCES portfolio_categories(id)");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE portfolio_projects_categories DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE portfolio_projects_categories DROP FOREIGN KEY ");
        $this->addSql("DROP TABLE portfolio_projects");
        $this->addSql("DROP TABLE portfolio_projects_categories");
        $this->addSql("DROP TABLE portfolio_categories");
    }
}