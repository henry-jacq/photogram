<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726194031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE follows (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, follower_id INT UNSIGNED NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_4B638A73A76ED395 (user_id), INDEX IDX_4B638A73AC24F853 (follower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE follows ADD CONSTRAINT FK_4B638A73A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE follows ADD CONSTRAINT FK_4B638A73AC24F853 FOREIGN KEY (follower_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE follows DROP FOREIGN KEY FK_4B638A73A76ED395');
        $this->addSql('ALTER TABLE follows DROP FOREIGN KEY FK_4B638A73AC24F853');
        $this->addSql('DROP TABLE follows');
    }
}
