<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308224310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_CE606404A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reclamation AS SELECT id, user_id, title, problem FROM reclamation');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('CREATE TABLE reclamation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL, problem VARCHAR(255) NOT NULL, CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reclamation (id, user_id, title, problem) SELECT id, user_id, title, problem FROM __temp__reclamation');
        $this->addSql('DROP TABLE __temp__reclamation');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
        $this->addSql('DROP INDEX IDX_C4BE635F91560F9D');
        $this->addSql('DROP INDEX IDX_C4BE635F2D6BA2D9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__responses_reclamation AS SELECT responses_id, reclamation_id FROM responses_reclamation');
        $this->addSql('DROP TABLE responses_reclamation');
        $this->addSql('CREATE TABLE responses_reclamation (responses_id INTEGER NOT NULL, reclamation_id INTEGER NOT NULL, PRIMARY KEY(responses_id, reclamation_id), CONSTRAINT FK_C4BE635F91560F9D FOREIGN KEY (responses_id) REFERENCES responses (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C4BE635F2D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO responses_reclamation (responses_id, reclamation_id) SELECT responses_id, reclamation_id FROM __temp__responses_reclamation');
        $this->addSql('DROP TABLE __temp__responses_reclamation');
        $this->addSql('CREATE INDEX IDX_C4BE635F91560F9D ON responses_reclamation (responses_id)');
        $this->addSql('CREATE INDEX IDX_C4BE635F2D6BA2D9 ON responses_reclamation (reclamation_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN github_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_CE606404A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reclamation AS SELECT id, user_id, title, problem FROM reclamation');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('CREATE TABLE reclamation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL, problem VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO reclamation (id, user_id, title, problem) SELECT id, user_id, title, problem FROM __temp__reclamation');
        $this->addSql('DROP TABLE __temp__reclamation');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
        $this->addSql('DROP INDEX IDX_C4BE635F91560F9D');
        $this->addSql('DROP INDEX IDX_C4BE635F2D6BA2D9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__responses_reclamation AS SELECT responses_id, reclamation_id FROM responses_reclamation');
        $this->addSql('DROP TABLE responses_reclamation');
        $this->addSql('CREATE TABLE responses_reclamation (responses_id INTEGER NOT NULL, reclamation_id INTEGER NOT NULL, PRIMARY KEY(responses_id, reclamation_id))');
        $this->addSql('INSERT INTO responses_reclamation (responses_id, reclamation_id) SELECT responses_id, reclamation_id FROM __temp__responses_reclamation');
        $this->addSql('DROP TABLE __temp__responses_reclamation');
        $this->addSql('CREATE INDEX IDX_C4BE635F91560F9D ON responses_reclamation (responses_id)');
        $this->addSql('CREATE INDEX IDX_C4BE635F2D6BA2D9 ON responses_reclamation (reclamation_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, password, roles, is_verified, image, telephone, address FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, is_verified BOOLEAN DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, username, email, password, roles, is_verified, image, telephone, address) SELECT id, username, email, password, roles, is_verified, image, telephone, address FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
