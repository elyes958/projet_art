<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519091859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, commande_id_id INT DEFAULT NULL, INDEX IDX_26A98456462C4194 (commande_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, total INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456462C4194 FOREIGN KEY (commande_id_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE user ADD user_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A76ED395 FOREIGN KEY (user_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A76ED395 ON user (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456462C4194');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A76ED395');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP INDEX IDX_8D93D649A76ED395 ON user');
        $this->addSql('ALTER TABLE user DROP user_id, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE username username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
