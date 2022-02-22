<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217194010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL, problem VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
        $this->addSql('CREATE TABLE responses (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, solution VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE responses_reclamation (responses_id INTEGER NOT NULL, reclamation_id INTEGER NOT NULL, PRIMARY KEY(responses_id, reclamation_id))');
        $this->addSql('CREATE INDEX IDX_C4BE635F91560F9D ON responses_reclamation (responses_id)');
        $this->addSql('CREATE INDEX IDX_C4BE635F2D6BA2D9 ON responses_reclamation (reclamation_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE responses');
        $this->addSql('DROP TABLE responses_reclamation');
        $this->addSql('DROP TABLE user');
    }
}
