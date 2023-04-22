<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230422020303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depense (id INT AUTO_INCREMENT NOT NULL, bande_id INT NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, created_at DATE NOT NULL, INDEX IDX_3405975711999B4A (bande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vente (id INT AUTO_INCREMENT NOT NULL, bande_id INT NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, created_at DATE NOT NULL, INDEX IDX_888A2A4C11999B4A (bande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_3405975711999B4A FOREIGN KEY (bande_id) REFERENCES bande (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C11999B4A FOREIGN KEY (bande_id) REFERENCES bande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_3405975711999B4A');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C11999B4A');
        $this->addSql('DROP TABLE depense');
        $this->addSql('DROP TABLE vente');
    }
}
