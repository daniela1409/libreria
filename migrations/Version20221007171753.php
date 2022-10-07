<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007171753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, edition VARCHAR(255) NOT NULL, create_at DATE NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_sale (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, sale_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_2D7B84FE16A2B381 (book_id), INDEX IDX_2D7B84FE4A7E4868 (sale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, create_at DATE NOT NULL, INDEX IDX_E54BC005A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, sex TINYINT(1) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', birthday_date DATE NOT NULL, create_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_sale ADD CONSTRAINT FK_2D7B84FE16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE item_sale ADD CONSTRAINT FK_2D7B84FE4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_sale DROP FOREIGN KEY FK_2D7B84FE16A2B381');
        $this->addSql('ALTER TABLE item_sale DROP FOREIGN KEY FK_2D7B84FE4A7E4868');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005A76ED395');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE item_sale');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE users');
    }
}
