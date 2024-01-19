<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119102604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE wallet_transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE exchange_rate (currency_from VARCHAR(3) NOT NULL, currency_to VARCHAR(3) NOT NULL, rate INT NOT NULL, PRIMARY KEY(currency_from, currency_to))');
        $this->addSql('CREATE TABLE wallet_transaction (id INT NOT NULL, wallet_id INT NOT NULL, type VARCHAR(255) NOT NULL, amount INT NOT NULL, currency VARCHAR(3) NOT NULL, reason VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7DAF972712520F3 ON wallet_transaction (wallet_id)');
        $this->addSql('ALTER TABLE wallet_transaction ADD CONSTRAINT FK_7DAF972712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE wallet_transaction_id_seq CASCADE');
        $this->addSql('ALTER TABLE wallet_transaction DROP CONSTRAINT FK_7DAF972712520F3');
        $this->addSql('DROP TABLE exchange_rate');
        $this->addSql('DROP TABLE wallet_transaction');
    }
}
