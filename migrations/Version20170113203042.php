<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Sets overageType to a sane default.
 */
class Version20170113203042 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->performDatabaseUpgrade();
        $adjustValueTypesSQL = "UPDATE ProjectPart SET overageType = 'absolute' where overageType = ''";
        $this->addSql($adjustValueTypesSQL);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}