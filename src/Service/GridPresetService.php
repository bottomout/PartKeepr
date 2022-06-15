<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\GridPreset;

class GridPresetService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function markGridPresetAsDefault(GridPreset $gridPreset)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->update("App:GridPreset", "gp")->set("gp.gridDefault", ":default")
        ->where("gp.grid = :grid");

        $queryBuilder->setParameter("grid", $gridPreset->getGrid());
        $queryBuilder->setParameter("default", false);

        $queryBuilder->getQuery()->execute();

        $gridPreset->setGridDefault(true);
    }

    public function getDefaultPresets()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select("gp.grid", "gp.configuration")->from("App:GridPreset", "gp")->where("gp.gridDefault = :default");
        $queryBuilder->setParameter("default", true);

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
