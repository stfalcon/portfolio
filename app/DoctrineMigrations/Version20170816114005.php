<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170816114005 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $dateTime = "'".(new \DateTime())->format('Y-m-d H:i:s')."'";

        $this->addSql('CREATE TABLE seo_homepage_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_8A81CF65232D562B (object_id), UNIQUE INDEX seo_homepage_lookup_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo_homepage (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, keywords VARCHAR(255) DEFAULT NULL, ogTitle VARCHAR(255) NOT NULL, ogDescription LONGTEXT NOT NULL, ogImage VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seo_homepage_translations ADD CONSTRAINT FK_8A81CF65232D562B FOREIGN KEY (object_id) REFERENCES seo_homepage (id) ON DELETE CASCADE');
        $this->addSql('
            INSERT INTO seo_homepage (title, description, ogTitle, ogDescription, ogImage, createdAt, updatedAt)
            VALUES (
                \'Веб студия разработки сайтов, дизайна сайтов. Разработка мобильных приложений\',
                \'Разработка и проектирование сложных сайтов на Symfony, разработка мобильных приложений и игр. Продающий дизайн сайтов и интерфейсов. Консультации по тел.:+380 67 334-40-40\',
                \'Веб студия разработки сайтов, дизайна сайтов. Разработка мобильных приложений | Web cтудия Stfalcon.com\',
                \'Разработка и проектирование сложных сайтов на Symfony, разработка мобильных приложений и игр. Продающий дизайн сайтов и интерфейсов. Консультации по тел.:+380 67 334-40-40\',
                \'og_image.png\',
                '.$dateTime.',
                '.$dateTime.'
            )
        ');

        $this->addSql('
            INSERT INTO seo_homepage_translations (object_id, locale, field, content) 
            VALUES (
                1, \'en\', \'title\', \'Web and mobile app development, web design company\'
            )
        ');
        $this->addSql('
            INSERT INTO seo_homepage_translations (object_id, locale, field, content) 
            VALUES (
                1, \'en\', \'keywords\', NULL
            )
        ');
        $this->addSql('
            INSERT INTO seo_homepage_translations (object_id, locale, field, content) 
            VALUES (
                1, \'en\', \'description\', \'Development and design of complex sites on Symfony2, development of mobile applications and games. Website design and interfaces that increase sales. Phone consultations. + 380 67 334-40-40\'
            )
        ');
        $this->addSql('
            INSERT INTO seo_homepage_translations (object_id, locale, field, content) 
            VALUES (
                1, \'en\', \'ogTitle\', \'Web and mobile app development, web design company | Stfalcon.com\'
            )
        ');
        $this->addSql('
            INSERT INTO seo_homepage_translations (object_id, locale, field, content) 
            VALUES (
                1, \'en\', \'ogDescription\', \'Development and design of complex sites on Symfony2, development of mobile applications and games. Website design and interfaces that increase sales. Phone consultations. + 380 67 334-40-40\'
            )
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seo_homepage_translations DROP FOREIGN KEY FK_8A81CF65232D562B');
        $this->addSql('DROP TABLE seo_homepage_translations');
        $this->addSql('DROP TABLE seo_homepage');
    }
}
