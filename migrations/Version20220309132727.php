<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309132727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events_reservation (id INT AUTO_INCREMENT NOT NULL, reservations INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events_reservation_calendar (events_reservation_id INT NOT NULL, calendar_id INT NOT NULL, INDEX IDX_E1F7873C1D8506ED (events_reservation_id), INDEX IDX_E1F7873CA40A2C8 (calendar_id), PRIMARY KEY(events_reservation_id, calendar_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events_reservation_calendar ADD CONSTRAINT FK_E1F7873C1D8506ED FOREIGN KEY (events_reservation_id) REFERENCES events_reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_reservation_calendar ADD CONSTRAINT FK_E1F7873CA40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events_reservation_calendar DROP FOREIGN KEY FK_E1F7873C1D8506ED');
        $this->addSql('DROP TABLE events_reservation');
        $this->addSql('DROP TABLE events_reservation_calendar');
    }
}
