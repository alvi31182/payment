<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107084313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment (
                                id UUID NOT NULL, 
                                player_id UUID NOT NULL,
                                amount NUMERIC(10, 0) NOT NULL, 
                                currency VARCHAR(255) NOT NULL,
                                amount_type VARCHAR(255) NOT NULL, 
                                created_at timestamp NOT NULL, 
                                updated_at timestamp DEFAULT NULL,                 
                                PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX btree_payment_player_idx ON payment (id, player_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payment');
    }
}
