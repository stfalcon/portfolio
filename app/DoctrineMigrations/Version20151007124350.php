<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151007124350 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_comments (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, post_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, language VARCHAR(10) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, INDEX IDX_2BC3B20D727ACA70 (parent_id), INDEX IDX_2BC3B20D4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20D727ACA70 FOREIGN KEY (parent_id) REFERENCES blog_comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20D4B89032C FOREIGN KEY (post_id) REFERENCES blog_posts (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_comments DROP FOREIGN KEY FK_2BC3B20D727ACA70');
        $this->addSql('DROP TABLE blog_comments');
    }
}
