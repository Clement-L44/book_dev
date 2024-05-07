<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514173517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Relation Tag:Tag (Child_tag - Parent_tag)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag RENAME COLUMN child_tag TO parent_tag_id');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783F5C1A0D7 FOREIGN KEY (parent_tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_389B783F5C1A0D7 ON tag (parent_tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B783F5C1A0D7');
        $this->addSql('DROP INDEX IDX_389B783F5C1A0D7');
        $this->addSql('ALTER TABLE tag RENAME COLUMN parent_tag_id TO child_tag');
    }
}
