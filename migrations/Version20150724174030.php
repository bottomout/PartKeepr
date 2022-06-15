<?php

namespace DoctrineMigrations;

use App\BaseMigration;
use Doctrine\DBAL\Schema\Schema;
use App\Entity\StorageLocationCategory;

/**
 * Migrates all storage locations to support categories. Creates a new root category if it doesn't exist, then applies
 * the root category to all storage locations.
 */
class Version20150724174030 extends BaseMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->performDatabaseUpgrade();

        $repository = $this->getEM()->getRepository(
            'App:StorageLocationCategory'
        );

        $rootNodes = $repository->getRootNodes();

        if (count($rootNodes) === 0) {
            // Ensure that the root category exists
            $rootNode = new StorageLocationCategory();
            $rootNode->setName('Root Node');
            $this->getEM()->persist($rootNode);
            $this->getEM()->flush();
        } else {
            $rootNode = array_values($rootNodes)[0];
        }

        $storageLocationRepository = $this->getEM()->getRepository(
            'App:StorageLocation'
        );

        $allStorageLocations = $storageLocationRepository->findAll();

        foreach ($allStorageLocations as $storageLocation) {
            $storageLocation->setCategory($rootNode);
        }
        $this->getEM()->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
    }
}
