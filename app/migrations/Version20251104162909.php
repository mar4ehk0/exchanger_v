<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251104162909 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE currency (
                id SERIAL NOT NULL,
                num_code VARCHAR(255) NOT NULL,
                char_code VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6956883F466B3976 ON currency (num_code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6956883F7D04EC4 ON currency (char_code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE currency');
    }
}
