<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220818120307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EA6D70A54');
        $this->addSql('DROP INDEX IDX_1F1B251EA6D70A54 ON item');
        $this->addSql('ALTER TABLE item CHANGE list_id_id list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E3DAE168B FOREIGN KEY (list_id) REFERENCES lists (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E3DAE168B ON item (list_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E3DAE168B');
        $this->addSql('DROP INDEX IDX_1F1B251E3DAE168B ON item');
        $this->addSql('ALTER TABLE item CHANGE list_id list_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA6D70A54 FOREIGN KEY (list_id_id) REFERENCES lists (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1F1B251EA6D70A54 ON item (list_id_id)');
    }
}
