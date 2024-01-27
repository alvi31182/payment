<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119093611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment ALTER COLUMN account_id DROP NOT NULL');
        $this->addSql('ALTER TABLE payment DROP COLUMN account_id');
    }

    public function down(Schema $schema): void
    {

    }
}
