<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427121402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748A6B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748A6B3CA4B FOREIGN KEY (id_user) REFERENCES User (id_user)');
        $this->addSql('DROP TABLE codepromo');
        $this->addSql('DROP TABLE ratings');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_2');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_1');
        $this->addSql('ALTER TABLE livraison CHANGE id_commande id_commande INT DEFAULT NULL, CHANGE id_client id_client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_24FD1DBC3E314AE8 FOREIGN KEY (id_commande) REFERENCES commande (id_command)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_24FD1DBCE173B1B8 FOREIGN KEY (id_client) REFERENCES User (id_user)');
        $this->addSql('ALTER TABLE livraison RENAME INDEX id_commande TO IDX_24FD1DBC3E314AE8');
        $this->addSql('ALTER TABLE livraison RENAME INDEX id_client TO IDX_24FD1DBCE173B1B8');
        $this->addSql('DROP INDEX id_code_promo ON user');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE ban ban TINYINT(1) NOT NULL, CHANGE id_code_promo id_code_promo INT NOT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY annonces_ibfk_1');
        $this->addSql('ALTER TABLE annonces CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F6B3CA4B FOREIGN KEY (id_user) REFERENCES User (id_user)');
        $this->addSql('ALTER TABLE annonces RENAME INDEX id_user TO IDX_CB988C6F6B3CA4B');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY avis_ibfk_3');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY avis_ibfk_2');
        $this->addSql('ALTER TABLE avis CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_produit id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF06B3CA4B FOREIGN KEY (id_user) REFERENCES User (id_user)');
        $this->addSql('ALTER TABLE avis RENAME INDEX id_produit TO IDX_8F91ABF0F7384557');
        $this->addSql('ALTER TABLE avis RENAME INDEX id_user TO IDX_8F91ABF06B3CA4B');
        $this->addSql('ALTER TABLE categorie CHANGE descriptionC description_c VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY commande_ibfk_1');
        $this->addSql('ALTER TABLE commande CHANGE id_produit id_produit INT DEFAULT NULL, CHANGE id_client id_client INT DEFAULT NULL, CHANGE montant_total montant_total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DE173B1B8 FOREIGN KEY (id_client) REFERENCES User (id_user)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE commande RENAME INDEX id_client TO IDX_6EEAA67DE173B1B8');
        $this->addSql('ALTER TABLE commande RENAME INDEX id_produit TO IDX_6EEAA67DF7384557');
        $this->addSql('ALTER TABLE customer CHANGE marque_produit marque_produit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE id_event id_event VARCHAR(255) NOT NULL, CHANGE date_event date_event DATETIME NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY produit_ibfk_1');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE produit RENAME INDEX produit_ibfk_1 TO IDX_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_2');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('DROP INDEX reservation_ibfk_2 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD id_user INT DEFAULT NULL, DROP id_client, CHANGE id_event id_event VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES User (id_user)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D52B4B97 FOREIGN KEY (id_event) REFERENCES evenement (id_event)');
        $this->addSql('CREATE INDEX IDX_42C849556B3CA4B ON reservation (id_user)');
        $this->addSql('ALTER TABLE reservation RENAME INDEX reservation_ibfk_1 TO IDX_42C84955D52B4B97');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE codepromo (id_code_promo INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, Montant_Reduction INT NOT NULL, Statut VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Utilisations_Restantes INT NOT NULL, PRIMARY KEY(id_code_promo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_Recette INT DEFAULT NULL, rating INT DEFAULT NULL, INDEX id_Recette (id_Recette), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748A6B3CA4B');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F6B3CA4B');
        $this->addSql('ALTER TABLE annonces CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT annonces_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonces RENAME INDEX idx_cb988c6f6b3ca4b TO id_user');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0F7384557');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF06B3CA4B');
        $this->addSql('ALTER TABLE avis CHANGE id_produit id_produit INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT avis_ibfk_3 FOREIGN KEY (id_produit) REFERENCES produit (id_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT avis_ibfk_2 FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis RENAME INDEX idx_8f91abf0f7384557 TO id_produit');
        $this->addSql('ALTER TABLE avis RENAME INDEX idx_8f91abf06b3ca4b TO id_user');
        $this->addSql('ALTER TABLE categorie CHANGE description_c descriptionC VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DE173B1B8');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF7384557');
        $this->addSql('ALTER TABLE commande CHANGE id_client id_client INT NOT NULL, CHANGE id_produit id_produit INT NOT NULL, CHANGE montant_total montant_total INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_1 FOREIGN KEY (id_produit) REFERENCES produit (id_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande RENAME INDEX idx_6eeaa67df7384557 TO id_produit');
        $this->addSql('ALTER TABLE commande RENAME INDEX idx_6eeaa67de173b1b8 TO id_client');
        $this->addSql('ALTER TABLE customer CHANGE marque_produit marque_produit VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE id_event id_event INT AUTO_INCREMENT NOT NULL, CHANGE date_event date_event DATE NOT NULL');
        $this->addSql('ALTER TABLE Livraison DROP FOREIGN KEY FK_24FD1DBC3E314AE8');
        $this->addSql('ALTER TABLE Livraison DROP FOREIGN KEY FK_24FD1DBCE173B1B8');
        $this->addSql('ALTER TABLE Livraison CHANGE id_commande id_commande INT NOT NULL, CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE Livraison ADD CONSTRAINT livraison_ibfk_2 FOREIGN KEY (id_commande) REFERENCES commande (id_command) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Livraison ADD CONSTRAINT livraison_ibfk_1 FOREIGN KEY (id_client) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Livraison RENAME INDEX idx_24fd1dbc3e314ae8 TO id_commande');
        $this->addSql('ALTER TABLE Livraison RENAME INDEX idx_24fd1dbce173b1b8 TO id_client');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT produit_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit RENAME INDEX idx_29a5ec27c9486a13 TO produit_ibfk_1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D52B4B97');
        $this->addSql('DROP INDEX IDX_42C849556B3CA4B ON reservation');
        $this->addSql('ALTER TABLE reservation ADD id_client INT NOT NULL, DROP id_user, CHANGE id_event id_event INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_2 FOREIGN KEY (id_client) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (id_event) REFERENCES evenement (id_event) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX reservation_ibfk_2 ON reservation (id_client)');
        $this->addSql('ALTER TABLE reservation RENAME INDEX idx_42c84955d52b4b97 TO reservation_ibfk_1');
        $this->addSql('ALTER TABLE User CHANGE roles roles JSON DEFAULT \'NULL\', CHANGE ban ban TINYINT(1) DEFAULT 0 NOT NULL, CHANGE id_code_promo id_code_promo INT DEFAULT 1 NOT NULL, CHANGE token token VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('CREATE INDEX id_code_promo ON User (id_code_promo)');
    }
}
