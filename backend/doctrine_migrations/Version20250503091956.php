<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250503091956 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
            CREATE TABLE user (
                uuid VARCHAR(36) NOT NULL,
                username VARCHAR(64) NOT NULL,
                password VARCHAR(255) NOT NULL,
                display_name VARCHAR(64) NOT NULL,
                roles JSON NOT NULL,
                UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
                UNIQUE INDEX UNIQ_8D93D649D5499347 (display_name),
                PRIMARY KEY(uuid)
            ) DEFAULT CHARACTER SET utf8mb4
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
            DROP TABLE user
            SQL
        );
    }
}
