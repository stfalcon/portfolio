<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131230143822 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE users_users_groups (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_ACCE91ECA76ED395 (user_id), INDEX IDX_ACCE91ECFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE users_users_groups ADD CONSTRAINT FK_ACCE91ECA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)");
        $this->addSql("ALTER TABLE users_users_groups ADD CONSTRAINT FK_ACCE91ECFE54D947 FOREIGN KEY (group_id) REFERENCES users_groups (id)");
        $this->addSql("DROP TABLE fos_user_user_group");
        $this->addSql("ALTER TABLE users ADD avatar_name VARCHAR(255) DEFAULT NULL, DROP image_name, CHANGE company_position company_position VARCHAR(255) DEFAULT NULL, CHANGE caricature_name caricature_name VARCHAR(255) DEFAULT NULL, CHANGE interests interests VARCHAR(500) DEFAULT NULL, CHANGE drink drink VARCHAR(100) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES users_groups (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        $this->addSql("DROP TABLE users_users_groups");
        $this->addSql("ALTER TABLE users ADD image_name VARCHAR(255) NOT NULL, DROP avatar_name, CHANGE caricature_name caricature_name VARCHAR(255) NOT NULL, CHANGE company_position company_position VARCHAR(255) NOT NULL, CHANGE interests interests VARCHAR(500) NOT NULL, CHANGE drink drink VARCHAR(100) NOT NULL");
    }
}
