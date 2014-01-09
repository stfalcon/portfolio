<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131220143303 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE projects_media (project_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_FFADF532166D1F9C (project_id), INDEX IDX_FFADF532EA9FDD75 (media_id), PRIMARY KEY(project_id, media_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE projects_media ADD CONSTRAINT FK_FFADF532166D1F9C FOREIGN KEY (project_id) REFERENCES portfolio_projects (id)");
        $this->addSql("ALTER TABLE projects_media ADD CONSTRAINT FK_FFADF532EA9FDD75 FOREIGN KEY (media_id) REFERENCES portfolio_media_gallery_has_media (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE projects_media");
    }
}
