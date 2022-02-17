<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216150856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, problem VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE response (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, solution VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE response_reclamation (response_id INTEGER NOT NULL, reclamation_id INTEGER NOT NULL, PRIMARY KEY(response_id, reclamation_id))');
        $this->addSql('CREATE INDEX IDX_6EB97E8BFBF32840 ON response_reclamation (response_id)');
        $this->addSql('CREATE INDEX IDX_6EB97E8B2D6BA2D9 ON response_reclamation (reclamation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE response_reclamation');
    }
}
