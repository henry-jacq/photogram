<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723085853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_7AED79133950B5F6 ON user_sessions');
        $this->addSql('ALTER TABLE user_sessions ADD session_id VARCHAR(255) NOT NULL, ADD session_token VARCHAR(255) NOT NULL, ADD ip_address VARCHAR(255) NOT NULL, ADD user_agent VARCHAR(255) NOT NULL, ADD last_activity DATETIME NOT NULL, DROP sessionId, DROP ipAddress, DROP userAgent, CHANGE loginTime login_time DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7AED7913613FECDF ON user_sessions (session_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_7AED7913613FECDF ON user_sessions');
        $this->addSql('ALTER TABLE user_sessions ADD sessionId VARCHAR(255) NOT NULL, ADD ipAddress VARCHAR(255) NOT NULL, ADD userAgent VARCHAR(255) NOT NULL, ADD loginTime DATETIME NOT NULL, DROP session_id, DROP session_token, DROP ip_address, DROP user_agent, DROP login_time, DROP last_activity');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7AED79133950B5F6 ON user_sessions (sessionId)');
    }
}
