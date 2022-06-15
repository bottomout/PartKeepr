<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150608120000 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $averagePriceFixSQL = 'UPDATE Part SET averagePrice = 0 WHERE averagePrice IS NULL';
        $this->addSql($averagePriceFixSQL);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
