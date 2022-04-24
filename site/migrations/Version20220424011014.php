<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424011014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_27ACDEDCA76ED395');
        $this->addSql('DROP INDEX IDX_27ACDEDC4584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_shopping_basket AS SELECT id, user_id, product_id, quantity FROM im22_shopping_basket');
        $this->addSql('DROP TABLE im22_shopping_basket');
        $this->addSql('CREATE TABLE im22_shopping_basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_27ACDEDCA76ED395 FOREIGN KEY (user_id) REFERENCES im22_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_27ACDEDC4584665A FOREIGN KEY (product_id) REFERENCES im22_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im22_shopping_basket (id, user_id, product_id, quantity) SELECT id, user_id, product_id, quantity FROM __temp__im22_shopping_basket');
        $this->addSql('DROP TABLE __temp__im22_shopping_basket');
        $this->addSql('CREATE INDEX IDX_27ACDEDCA76ED395 ON im22_shopping_basket (user_id)');
        $this->addSql('CREATE INDEX IDX_27ACDEDC4584665A ON im22_shopping_basket (product_id)');
        $this->addSql('DROP INDEX UNIQ_B4392992AA08CB10');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_users AS SELECT id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin FROM im22_users');
        $this->addSql('DROP TABLE im22_users');
        $this->addSql('CREATE TABLE im22_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(60) NOT NULL, password VARCHAR(60) NOT NULL, surname VARCHAR(30) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , firstname VARCHAR(30) NOT NULL, date_of_birth DATE DEFAULT NULL, is_admin BOOLEAN DEFAULT 0 NOT NULL, is_super_admin BOOLEAN DEFAULT 0 NOT NULL)');
        $this->addSql('INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) SELECT id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin FROM __temp__im22_users');
        $this->addSql('DROP TABLE __temp__im22_users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4392992AA08CB10 ON im22_users (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_27ACDEDCA76ED395');
        $this->addSql('DROP INDEX IDX_27ACDEDC4584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_shopping_basket AS SELECT id, user_id, product_id, quantity FROM im22_shopping_basket');
        $this->addSql('DROP TABLE im22_shopping_basket');
        $this->addSql('CREATE TABLE im22_shopping_basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('INSERT INTO im22_shopping_basket (id, user_id, product_id, quantity) SELECT id, user_id, product_id, quantity FROM __temp__im22_shopping_basket');
        $this->addSql('DROP TABLE __temp__im22_shopping_basket');
        $this->addSql('CREATE INDEX IDX_27ACDEDCA76ED395 ON im22_shopping_basket (user_id)');
        $this->addSql('CREATE INDEX IDX_27ACDEDC4584665A ON im22_shopping_basket (product_id)');
        $this->addSql('DROP INDEX UNIQ_B4392992AA08CB10');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_users AS SELECT id, login, password, surname, roles, firstname, is_admin, is_super_admin, date_of_birth FROM im22_users');
        $this->addSql('DROP TABLE im22_users');
        $this->addSql('CREATE TABLE im22_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(60) NOT NULL, password VARCHAR(60) NOT NULL, surname VARCHAR(30) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , firstname VARCHAR(30) NOT NULL, is_admin BOOLEAN DEFAULT 0 NOT NULL, is_super_admin BOOLEAN DEFAULT 0 NOT NULL, date_of_birth DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO im22_users (id, login, password, surname, roles, firstname, is_admin, is_super_admin, date_of_birth) SELECT id, login, password, surname, roles, firstname, is_admin, is_super_admin, date_of_birth FROM __temp__im22_users');
        $this->addSql('DROP TABLE __temp__im22_users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4392992AA08CB10 ON im22_users (login)');
    }
}