<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151111114012 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE portfolio_users_with_positions (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, project_id INT NOT NULL, positions VARCHAR(255) DEFAULT NULL, INDEX IDX_B6710EBA76ED395 (user_id), INDEX IDX_B6710EB166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE portfolio_users_with_positions ADD CONSTRAINT FK_B6710EBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE portfolio_users_with_positions ADD CONSTRAINT FK_B6710EB166D1F9C FOREIGN KEY (project_id) REFERENCES portfolio_projects (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE portfolio_users_with_positions');
    }
}
