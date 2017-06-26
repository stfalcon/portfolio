<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170626201747 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jobs (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(128) NOT NULL, meta_keywords LONGTEXT DEFAULT NULL, meta_description LONGTEXT DEFAULT NULL, meta_title LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A8936DC5989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs_tags (jobs_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_43AB9C0048704627 (jobs_id), INDEX IDX_43AB9C00BAD26311 (tag_id), PRIMARY KEY(jobs_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jobs_tags ADD CONSTRAINT FK_43AB9C0048704627 FOREIGN KEY (jobs_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE jobs_tags ADD CONSTRAINT FK_43AB9C00BAD26311 FOREIGN KEY (tag_id) REFERENCES blog_tags (id)');
        $this->addSql('ALTER TABLE portfolio_projects_categories DROP FOREIGN KEY portfolio_projects_categories_ibfk_1');
        $this->addSql('ALTER TABLE portfolio_projects_categories DROP FOREIGN KEY portfolio_projects_categories_ibfk_2');
        $this->addSql('DROP INDEX portfolio_projects_categories_project_id_idx ON portfolio_projects_categories');
        $this->addSql('CREATE INDEX IDX_492ADF0C166D1F9C ON portfolio_projects_categories (project_id)');
        $this->addSql('DROP INDEX portfolio_projects_categories_category_id_idx ON portfolio_projects_categories');
        $this->addSql('CREATE INDEX IDX_492ADF0C12469DE2 ON portfolio_projects_categories (category_id)');
        $this->addSql('ALTER TABLE portfolio_projects_categories ADD CONSTRAINT portfolio_projects_categories_ibfk_1 FOREIGN KEY (project_id) REFERENCES portfolio_projects (id)');
        $this->addSql('ALTER TABLE portfolio_projects_categories ADD CONSTRAINT portfolio_projects_categories_ibfk_2 FOREIGN KEY (category_id) REFERENCES portfolio_categories (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jobs_tags DROP FOREIGN KEY FK_43AB9C0048704627');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE jobs_tags');
        $this->addSql('ALTER TABLE portfolio_projects_categories DROP FOREIGN KEY FK_492ADF0C166D1F9C');
        $this->addSql('ALTER TABLE portfolio_projects_categories DROP FOREIGN KEY FK_492ADF0C12469DE2');
        $this->addSql('DROP INDEX idx_492adf0c166d1f9c ON portfolio_projects_categories');
        $this->addSql('CREATE INDEX portfolio_projects_categories_project_id_idx ON portfolio_projects_categories (project_id)');
        $this->addSql('DROP INDEX idx_492adf0c12469de2 ON portfolio_projects_categories');
        $this->addSql('CREATE INDEX portfolio_projects_categories_category_id_idx ON portfolio_projects_categories (category_id)');
        $this->addSql('ALTER TABLE portfolio_projects_categories ADD CONSTRAINT FK_492ADF0C166D1F9C FOREIGN KEY (project_id) REFERENCES portfolio_projects (id)');
        $this->addSql('ALTER TABLE portfolio_projects_categories ADD CONSTRAINT FK_492ADF0C12469DE2 FOREIGN KEY (category_id) REFERENCES portfolio_categories (id)');
    }
}
