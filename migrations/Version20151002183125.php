<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Re-saves all parts in order to re-generate the averagePrice and removals field.
 */
class Version20151002183125 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->performDatabaseUpgrade();

        $partRepository = $this->getEM()->getRepository(
            'App:Part'
        );

        $parts = $partRepository->findAll();

        foreach ($parts as $part) {
            $part->recomputeStockLevels();
        }

        $this->getEM()->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
