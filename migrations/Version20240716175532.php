<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716175532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_data (user_id INT NOT NULL, fullname VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, job_title VARCHAR(255) NOT NULL, bio VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, instagram_handle VARCHAR(255) NOT NULL, linkedin_handle VARCHAR(255) NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, reset_token VARCHAR(255) NOT NULL, reset_token_expiry DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_data');
        $this->addSql('DROP TABLE users');
    }
}
