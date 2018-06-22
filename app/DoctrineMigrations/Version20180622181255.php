<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180622181255 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post_category_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_BCD02517232D562B (object_id), UNIQUE INDEX post_category_lookup_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_category_translations ADD CONSTRAINT FK_BCD02517232D562B FOREIGN KEY (object_id) REFERENCES post_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_posts ADD posts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932D5E258C5 FOREIGN KEY (posts_id) REFERENCES post_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_78B2F932D5E258C5 ON blog_posts (posts_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_posts DROP FOREIGN KEY FK_78B2F932D5E258C5');
        $this->addSql('ALTER TABLE post_category_translations DROP FOREIGN KEY FK_BCD02517232D562B');
        $this->addSql('DROP TABLE post_category_translations');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP INDEX IDX_78B2F932D5E258C5 ON blog_posts');
        $this->addSql('ALTER TABLE blog_posts DROP posts_id');
    }
}
