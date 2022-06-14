<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\SystemNotice;

class SystemNoticeService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUniqueSystemNotice($type, $title, $description)
    {
        $dql = "SELECT sn FROM App\Entity\SystemNotice sn WHERE sn.type = :type";
        $query = $this->entityManager->createQuery($dql);

        $query->setParameter('type', $type);

        try {
            $notice = $query->getSingleResult();
        } catch (\Exception $e) {
            $notice = new SystemNotice();
            $this->entityManager->persist($notice);
        }

        $notice->setDate(new \DateTime());
        $notice->setTitle($title);
        $notice->setDescription($description);
        $notice->setType($type);

        $this->entityManager->flush();

        return $notice;
    }
}
