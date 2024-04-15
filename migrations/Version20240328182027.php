<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328182027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495599DED506');
        $this->addSql('DROP INDEX IDX_42C8495599DED506 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE id_client_id idClient INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A455ACCF FOREIGN KEY (idClient) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_42C84955A455ACCF ON reservation (idClient)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A455ACCF');
        $this->addSql('DROP INDEX IDX_42C84955A455ACCF ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE idClient id_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495599DED506 FOREIGN KEY (id_client_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42C8495599DED506 ON reservation (id_client_id)');
    }
}
