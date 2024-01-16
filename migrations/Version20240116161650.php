<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116161650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE event_storage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_storage (
                            id BIGINT NOT NULL, 
                            event_name VARCHAR(255) NOT NULL, 
                            event_data JSONB NOT NULL, 
                            created_at timestamp NOT NULL, 
                            PRIMARY KEY(id)
                           )'
        );
        $this->addSql('COMMENT ON COLUMN event_storage.event_data IS \'(DC2Type:jsonb)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_storage');
    }
}
