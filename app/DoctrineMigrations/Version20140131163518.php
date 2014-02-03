<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140131163518 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE portfolio_media_gallery_has_media DROP FOREIGN KEY FK_10FF06AE4E7AF8F");
        $this->addSql("DROP INDEX IDX_10FF06AE4E7AF8F ON portfolio_media_gallery_has_media");
        $this->addSql("ALTER TABLE portfolio_media_gallery_has_media CHANGE gallery_id galery_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE portfolio_media_gallery_has_media ADD CONSTRAINT FK_10FF06AEDA40A005 FOREIGN KEY (galery_id) REFERENCES portfolio_media_gallery (id)");
        $this->addSql("CREATE INDEX IDX_10FF06AEDA40A005 ON portfolio_media_gallery_has_media (galery_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE portfolio_media_gallery_has_media DROP FOREIGN KEY FK_10FF06AEDA40A005");
        $this->addSql("DROP INDEX IDX_10FF06AEDA40A005 ON portfolio_media_gallery_has_media");
        $this->addSql("ALTER TABLE portfolio_media_gallery_has_media CHANGE galery_id gallery_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE portfolio_media_gallery_has_media ADD CONSTRAINT FK_10FF06AE4E7AF8F FOREIGN KEY (gallery_id) REFERENCES portfolio_media_gallery (id)");
        $this->addSql("CREATE INDEX IDX_10FF06AE4E7AF8F ON portfolio_media_gallery_has_media (gallery_id)");
    }
}
