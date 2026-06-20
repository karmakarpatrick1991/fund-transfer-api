<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260621000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating accounts and transfer tables';
    }

    public function up(Schema $schema): void
    {
        $number = random_int(100000, 999999);
        try {
            $this->addSql("
            CREATE TABLE accounts (
                id INT AUTO_INCREMENT NOT NULL,
                uuid VARCHAR(50) NOT NULL,
                balance NUMERIC(18,2) NOT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY(id),
                UNIQUE INDEX idx_uuid (uuid)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB
            AUTO_INCREMENT = $number
        ");
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        try {
            $this->addSql("
            CREATE TABLE transfer (
                id INT AUTO_INCREMENT NOT NULL,
                uuid VARCHAR(50) NOT NULL,
                source_account_id INT NOT NULL,
                destination_account_id INT NOT NULL,
                amount NUMERIC(18,2) NOT NULL,
                source_balance_before NUMERIC(18,2) DEFAULT NULL,
                source_balance_after NUMERIC(18,2) DEFAULT NULL,
                destination_balance_before NUMERIC(18,2) DEFAULT NULL,
                destination_balance_after NUMERIC(18,2) DEFAULT NULL,
                status VARCHAR(30) NOT NULL,
                idempotency_key VARCHAR(197) NOT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY(id),
                UNIQUE INDEX idx_idempotency_key (idempotency_key),
                INDEX idx_source_account_id (source_account_id),
                INDEX idx_destination_account_id (destination_account_id),
                INDEX idx_source_destination_account
                (source_account_id, destination_account_id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB
        ");
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function down(Schema $schema): void
    {
        return;
    }
}
