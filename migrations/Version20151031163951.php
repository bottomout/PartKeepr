<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Ensures that each footprint has a category assigned.
 */
class Version20151031163951 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $footprintRepository = $this->getEM()->getRepository(
            'App:Footprint'
        );

        $rootNode = $this->getContainer()->get('partkeepr.footprint.category_service')->getRootNode();

        $footprints = $footprintRepository->findAll();

        foreach ($footprints as $footprint) {
            if ($footprint->getCategory() === null) {
                $footprint->setCategory($rootNode);
            }
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
