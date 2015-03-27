<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150326170729 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE relative_projects_ref (project_id INT NOT NULL, relative_project_id INT NOT NULL, INDEX IDX_E48AE64B166D1F9C (project_id), UNIQUE INDEX UNIQ_E48AE64BBE7DCDB9 (relative_project_id), PRIMARY KEY(project_id, relative_project_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relative_projects_ref ADD CONSTRAINT FK_E48AE64B166D1F9C FOREIGN KEY (project_id) REFERENCES portfolio_projects (id)');
        $this->addSql('ALTER TABLE relative_projects_ref ADD CONSTRAINT FK_E48AE64BBE7DCDB9 FOREIGN KEY (relative_project_id) REFERENCES portfolio_projects (id)');
        $this->addSql('ALTER TABLE portfolio_projects ADD case_content LONGTEXT DEFAULT NULL, ADD show_case TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE relative_projects_ref');
        $this->addSql('ALTER TABLE portfolio_projects DROP case_content, DROP show_case');
    }
}
