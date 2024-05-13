<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513172858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, main_referee_id INT DEFAULT NULL, location VARCHAR(32) NOT NULL, local_team VARCHAR(32) NOT NULL, guest_team VARCHAR(32) NOT NULL, sub_referee VARCHAR(32) NOT NULL, date DATETIME NOT NULL, status VARCHAR(32) NOT NULL, status_update DATETIME NOT NULL, INDEX IDX_232B318C72F008C1 (main_referee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_event (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, type VARCHAR(16) NOT NULL, value JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_99D7328E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, username VARCHAR(32) NOT NULL, password TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C72F008C1 FOREIGN KEY (main_referee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_event ADD CONSTRAINT FK_99D7328E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C72F008C1');
        $this->addSql('ALTER TABLE game_event DROP FOREIGN KEY FK_99D7328E48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_event');
        $this->addSql('DROP TABLE user');
    }
}
