<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251106135042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency_rate (id SERIAL NOT NULL, currency_id INT NOT NULL, value NUMERIC(15, 6) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_555B7C4D38248176 ON currency_rate (currency_id)');
        $this->addSql('ALTER TABLE currency_rate ADD CONSTRAINT FK_555B7C4D38248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE currency_rate DROP CONSTRAINT FK_555B7C4D38248176');
        $this->addSql('DROP TABLE currency_rate');
    }
}
