<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120184519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO exchange_rate (currency_from, currency_to, rate, updated_at) VALUES
            ('rub', 'usd', 1, current_date)");

        $this->addSql("INSERT INTO exchange_rate (currency_from, currency_to, rate, updated_at) VALUES
            ('usd', 'rub', 8943, current_date)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
