<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411231559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im22_products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(30) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE im22_shopping_basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, product_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_27ACDEDCA76ED395 ON im22_shopping_basket (user_id)');
        $this->addSql('CREATE INDEX IDX_27ACDEDC4584665A ON im22_shopping_basket (product_id)');
        $this->addSql('CREATE TABLE im22_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(60) NOT NULL, password VARCHAR(60) NOT NULL, surname VARCHAR(30) NOT NULL, firstname VARCHAR(30) NOT NULL, date_of_birth DATETIME DEFAULT NULL, is_admin BOOLEAN DEFAULT 0 NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE im22_products');
        $this->addSql('DROP TABLE im22_shopping_basket');
        $this->addSql('DROP TABLE im22_users');
    }
}
