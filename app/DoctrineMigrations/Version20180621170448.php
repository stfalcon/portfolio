<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180621170448 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE landings_projects (landing_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_3AC9BFBAEFD98736 (landing_id), INDEX IDX_3AC9BFBA166D1F9C (project_id), PRIMARY KEY(landing_id, project_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE landings_posts (landing_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_88C2DFE9EFD98736 (landing_id), INDEX IDX_88C2DFE94B89032C (post_id), PRIMARY KEY(landing_id, post_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE landings_projects ADD CONSTRAINT FK_3AC9BFBAEFD98736 FOREIGN KEY (landing_id) REFERENCES portfolio_landing (id)');
        $this->addSql('ALTER TABLE landings_projects ADD CONSTRAINT FK_3AC9BFBA166D1F9C FOREIGN KEY (project_id) REFERENCES portfolio_projects (id)');
        $this->addSql('ALTER TABLE landings_posts ADD CONSTRAINT FK_88C2DFE9EFD98736 FOREIGN KEY (landing_id) REFERENCES portfolio_landing (id)');
        $this->addSql('ALTER TABLE landings_posts ADD CONSTRAINT FK_88C2DFE94B89032C FOREIGN KEY (post_id) REFERENCES blog_posts (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE landings_projects');
        $this->addSql('DROP TABLE landings_posts');
    }
}
