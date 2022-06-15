<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Ensures that all existing parts to be no meta parts since prior to that version, no meta parts existed.
 */
class Version20170108122512 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->performDatabaseUpgrade();
        $noMetaPartSQL = 'UPDATE Part SET metaPart = false';
        $this->addSql($noMetaPartSQL);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
