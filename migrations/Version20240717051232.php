<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717051232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT UNSIGNED AUTO_INCREMENT NOT NULL, post_id INT UNSIGNED NOT NULL, post_owner_id INT UNSIGNED NOT NULL, comment_user_id INT UNSIGNED NOT NULL, content LONGTEXT NOT NULL, comment_date DATETIME NOT NULL, INDEX IDX_5F9E962A4B89032C (post_id), INDEX IDX_5F9E962AC1D1E858 (post_owner_id), INDEX IDX_5F9E962A541DB185 (comment_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT UNSIGNED AUTO_INCREMENT NOT NULL, post_id INT UNSIGNED NOT NULL, image_path VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6A4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, post_id INT UNSIGNED NOT NULL, post_owner_id INT UNSIGNED NOT NULL, liked_user_id INT UNSIGNED NOT NULL, INDEX IDX_49CA4E7D4B89032C (post_id), INDEX IDX_49CA4E7DC1D1E858 (post_owner_id), INDEX IDX_49CA4E7DDD7690DF (liked_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, payment_date DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, payment_status ENUM(\'pending\', \'completed\', \'failed\') NOT NULL, transaction_id VARCHAR(255) NOT NULL, payment_gateway VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_65D29B322FC0CB0F (transaction_id), INDEX IDX_65D29B32A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, caption LONGTEXT DEFAULT NULL, upload_date DATETIME NOT NULL, is_archived TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_885DBAFAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriptions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, plan VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, billing_cycle VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_4778A01A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AC1D1E858 FOREIGN KEY (post_owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A541DB185 FOREIGN KEY (comment_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DC1D1E858 FOREIGN KEY (post_owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DDD7690DF FOREIGN KEY (liked_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE subscriptions ADD CONSTRAINT FK_4778A01A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_data ADD full_name VARCHAR(255) DEFAULT NULL, ADD profile_avatar VARCHAR(255) DEFAULT NULL, ADD twitter_handle VARCHAR(255) DEFAULT NULL, ADD facebook_handle VARCHAR(255) DEFAULT NULL, DROP fullname, DROP avatar, CHANGE user_id user_id INT UNSIGNED NOT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL, CHANGE job_title job_title VARCHAR(255) DEFAULT NULL, CHANGE bio bio LONGTEXT DEFAULT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL, CHANGE instagram_handle instagram_handle VARCHAR(255) DEFAULT NULL, CHANGE linkedin_handle linkedin_handle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_data ADD CONSTRAINT FK_D772BFAAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL, CHANGE reset_token_expiry reset_token_expiry DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AC1D1E858');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A541DB185');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A4B89032C');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D4B89032C');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DC1D1E858');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DDD7690DF');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B32A76ED395');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAA76ED395');
        $this->addSql('ALTER TABLE subscriptions DROP FOREIGN KEY FK_4778A01A76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE subscriptions');
        $this->addSql('ALTER TABLE users CHANGE reset_token reset_token VARCHAR(255) NOT NULL, CHANGE reset_token_expiry reset_token_expiry DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_data DROP FOREIGN KEY FK_D772BFAAA76ED395');
        $this->addSql('ALTER TABLE user_data ADD fullname VARCHAR(255) NOT NULL, ADD avatar VARCHAR(255) NOT NULL, DROP full_name, DROP profile_avatar, DROP twitter_handle, DROP facebook_handle, CHANGE user_id user_id INT NOT NULL, CHANGE website website VARCHAR(255) NOT NULL, CHANGE job_title job_title VARCHAR(255) NOT NULL, CHANGE bio bio VARCHAR(255) NOT NULL, CHANGE location location VARCHAR(255) NOT NULL, CHANGE instagram_handle instagram_handle VARCHAR(255) NOT NULL, CHANGE linkedin_handle linkedin_handle VARCHAR(255) NOT NULL');
    }
}
