<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140303100439 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE blog_posts DROP FOREIGN KEY FK_78B2F932F675F31B");
        $this->addSql("ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932F675F31B FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE SET NULL");
        $this->addSql("ALTER TABLE projects_participants DROP FOREIGN KEY FK_18326CC8A76ED395");
        $this->addSql("ALTER TABLE projects_participants ADD CONSTRAINT FK_18326CC8A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE blog_posts DROP FOREIGN KEY FK_78B2F932F675F31B");
        $this->addSql("ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932F675F31B FOREIGN KEY (author_id) REFERENCES users (id)");
        $this->addSql("ALTER TABLE projects_participants DROP FOREIGN KEY FK_18326CC8A76ED395");
        $this->addSql("ALTER TABLE projects_participants ADD CONSTRAINT FK_18326CC8A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)");
    }
}
