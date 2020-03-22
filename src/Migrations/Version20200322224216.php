<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200322224216 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B76C8A81A9');
        $this->addSql('DROP INDEX IDX_BA388B76C8A81A9 ON cart');
        $this->addSql('ALTER TABLE cart DROP products_id');
        $this->addSql('ALTER TABLE product ADD carts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBCB5C6F5 FOREIGN KEY (carts_id) REFERENCES cart (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D34A04ADBCB5C6F5 ON product (carts_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart ADD products_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B76C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BA388B76C8A81A9 ON cart (products_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBCB5C6F5');
        $this->addSql('DROP INDEX IDX_D34A04ADBCB5C6F5 ON product');
        $this->addSql('ALTER TABLE product DROP carts_id');
    }
}
