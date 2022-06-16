<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Updates the value type to "numeric" where no value type is set.
 */
class Version20170108143802 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->performDatabaseUpgrade();
        $adjustValueTypesSQL = "UPDATE PartParameter SET valueType = 'numeric' where valueType = ''";
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