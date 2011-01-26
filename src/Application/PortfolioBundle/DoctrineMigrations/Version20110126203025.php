<?php

namespace PortfolioBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20110126203025 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->_addSql("CREATE TABLE portfolio_projects_categories (project_id INT NOT NULL, category_id INT NOT NULL, INDEX portfolio_projects_categories_project_id_idx (project_id), INDEX portfolio_projects_categories_category_id_idx (category_id), PRIMARY KEY(project_id, category_id)) ENGINE = InnoDB");
        $this->_addSql("ALTER TABLE portfolio_projects_categories ADD FOREIGN KEY (project_id) REFERENCES portfolio_projects(id)");
        $this->_addSql("ALTER TABLE portfolio_projects_categories ADD FOREIGN KEY (category_id) REFERENCES portfolio_categories(id)");
    }

    public function down(Schema $schema)
    {
        $this->_addSql("DROP TABLE portfolio_projects_categories");
    }
}