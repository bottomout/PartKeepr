<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Sets all present users to be active.
 */
class Version20151208162723 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->performDatabaseUpgrade();
        $activeUsersSQL = 'UPDATE PartKeeprUser SET active = true';
        $this->addSql($activeUsersSQL);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}