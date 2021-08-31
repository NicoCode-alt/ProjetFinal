<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831121620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE critic ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE critic ADD CONSTRAINT FK_C9E2F7F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C9E2F7F1A76ED395 ON critic (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE critic DROP FOREIGN KEY FK_C9E2F7F1A76ED395');
        $this->addSql('DROP INDEX IDX_C9E2F7F1A76ED395 ON critic');
        $this->addSql('ALTER TABLE critic DROP user_id');
    }
}
