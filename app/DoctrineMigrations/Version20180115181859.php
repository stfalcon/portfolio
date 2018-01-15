<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115181859 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project_reviewer_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_79E23B21232D562B (object_id), UNIQUE INDEX project_reviewer_lookup_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_review_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_A579224C232D562B (object_id), UNIQUE INDEX project_review_lookup_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE portfolio_projects_reviewer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE portfolio_projects_review (id INT AUTO_INCREMENT NOT NULL, reviewer_id INT DEFAULT NULL, project_id INT DEFAULT NULL, text LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, reviewer_project_status VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, INDEX IDX_133AB9E470574616 (reviewer_id), INDEX IDX_133AB9E4166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_reviewer_translations ADD CONSTRAINT FK_79E23B21232D562B FOREIGN KEY (object_id) REFERENCES portfolio_projects_reviewer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_review_translations ADD CONSTRAINT FK_A579224C232D562B FOREIGN KEY (object_id) REFERENCES portfolio_projects_review (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE portfolio_projects_review ADD CONSTRAINT FK_133AB9E470574616 FOREIGN KEY (reviewer_id) REFERENCES portfolio_projects_reviewer (id)');
        $this->addSql('ALTER TABLE portfolio_projects_review ADD CONSTRAINT FK_133AB9E4166D1F9C FOREIGN KEY (project_id) REFERENCES portfolio_projects (id)');
        $this->addSql('ALTER TABLE portfolio_projects ADD background_color VARCHAR(7) DEFAULT \'#4D9CC9\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_reviewer_translations DROP FOREIGN KEY FK_79E23B21232D562B');
        $this->addSql('ALTER TABLE portfolio_projects_review DROP FOREIGN KEY FK_133AB9E470574616');
        $this->addSql('ALTER TABLE project_review_translations DROP FOREIGN KEY FK_A579224C232D562B');
        $this->addSql('DROP TABLE project_reviewer_translations');
        $this->addSql('DROP TABLE project_review_translations');
        $this->addSql('DROP TABLE portfolio_projects_reviewer');
        $this->addSql('DROP TABLE portfolio_projects_review');
        $this->addSql('ALTER TABLE portfolio_projects DROP background_color');
    }
}
