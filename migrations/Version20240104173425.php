<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104173425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (
                                id UUID NOT NULL, 
                                amount VARCHAR(255) NOT NULL, 
                                currency VARCHAR(255) NOT NULL,
                                amount_type VARCHAR(255) NOT NULL,
                                player_id UUID NOT NULL,
                                created_at timestamp NOT NULL, 
                                updated_at timestamp DEFAULT NULL,
                                PRIMARY KEY(id))
                                ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payment');
    }
}
