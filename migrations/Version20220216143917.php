<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216143917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reclamation_reponse_id INTEGER DEFAULT NULL, title VARCHAR(100) NOT NULL, problem VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_CE606404F7C948D3 ON reclamation (reclamation_reponse_id)');
        $this->addSql('CREATE TABLE reclamation_reponse (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reponse_id INTEGER DEFAULT NULL, date DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_8E9C0056CF18BB82 ON reclamation_reponse (reponse_id)');
        $this->addSql('CREATE TABLE response (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, solution VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reclamation_reponse');
        $this->addSql('DROP TABLE response');
    }
}
