<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251221183445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(50) NOT NULL, ADD last_name VARCHAR(50) NOT NULL, ADD email_private VARCHAR(180) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD phone_pro VARCHAR(20) DEFAULT NULL, ADD company_id VARCHAR(50) DEFAULT NULL, ADD adress VARCHAR(255) DEFAULT NULL, ADD position VARCHAR(100) NOT NULL, ADD department VARCHAR(100) DEFAULT NULL, ADD avatar VARCHAR(255) DEFAULT NULL, ADD is_active TINYINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP first_name, DROP last_name, DROP email_private, DROP phone, DROP phone_pro, DROP company_id, DROP adress, DROP position, DROP department, DROP avatar, DROP is_active');
    }
}
