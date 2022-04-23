<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423183836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_27ACDEDC4584665A');
        $this->addSql('DROP INDEX UNIQ_27ACDEDCA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_shopping_basket AS SELECT id, user_id, product_id, quantity FROM im22_shopping_basket');
        $this->addSql('DROP TABLE im22_shopping_basket');
        $this->addSql('CREATE TABLE im22_shopping_basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_27ACDEDCA76ED395 FOREIGN KEY (user_id) REFERENCES im22_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_27ACDEDC4584665A FOREIGN KEY (product_id) REFERENCES im22_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im22_shopping_basket (id, user_id, product_id, quantity) SELECT id, user_id, product_id, quantity FROM __temp__im22_shopping_basket');
        $this->addSql('DROP TABLE __temp__im22_shopping_basket');
        $this->addSql('CREATE INDEX IDX_27ACDEDC4584665A ON im22_shopping_basket (product_id)');
        $this->addSql('CREATE INDEX IDX_27ACDEDCA76ED395 ON im22_shopping_basket (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_27ACDEDCA76ED395');
        $this->addSql('DROP INDEX IDX_27ACDEDC4584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_shopping_basket AS SELECT id, user_id, product_id, quantity FROM im22_shopping_basket');
        $this->addSql('DROP TABLE im22_shopping_basket');
        $this->addSql('CREATE TABLE im22_shopping_basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, product_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('INSERT INTO im22_shopping_basket (id, user_id, product_id, quantity) SELECT id, user_id, product_id, quantity FROM __temp__im22_shopping_basket');
        $this->addSql('DROP TABLE __temp__im22_shopping_basket');
        $this->addSql('CREATE INDEX IDX_27ACDEDC4584665A ON im22_shopping_basket (product_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_27ACDEDCA76ED395 ON im22_shopping_basket (user_id)');
    }
}
