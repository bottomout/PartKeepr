<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CronLogger;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class CronLoggerService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    /**
     * Marks a specific cronjob as ran.
     *
     * @param string $cronjob The name of the cronjob
     *
     * @return CronLogger The cron logger entity
     */
    public function markCronRun($cronjob)
    {
        $dql = "SELECT c FROM App\Entity\CronLogger c WHERE c.cronjob = :cronjob";
        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('cronjob', $cronjob);

        try {
            $result = $query->getSingleResult();
        } catch (\Exception $e) {
            $result = new CronLogger();
            $result->setCronjob($cronjob);
            $this->entityManager->persist($result);
        }

        $result->setLastRunDate(new \DateTime());

        $this->entityManager->flush();

        return $result;
    }

    /**
     * Returns a list of all inactive cronjobs.
     *
     * @param none
     *
     * @return array A string of cronjob names which aren't running
     */
    public function getInactiveCronjobs($requiredCronjobs)
    {
        $dql = "SELECT c.cronjob FROM App\Entity\CronLogger c WHERE c.cronjob = :cronjob";
        $dql .= ' AND c.lastRunDate > :date';

        $query = $this->entityManager->createQuery($dql);

        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));
        $query->setParameter('date', $date);

        $failedCronjobs = [];

        foreach ($requiredCronjobs as $cronjob) {
            $query->setParameter('cronjob', $cronjob);

            try {
                $query->getSingleResult();
            } catch (\Exception $e) {
                $failedCronjobs[] = $cronjob;
            }
        }

        return $failedCronjobs;
    }

    /**
     * Clears all cron logger entries.
     */
    public function clear()
    {
        $dql = "DELETE FROM App\Entity\CronLogger c";
        $query = $this->entityManager->createQuery($dql);

        $query->execute();
    }

    /**
     * Runs all crons.
     *
     * @throws \Exception
     */
    public function runCrons()
    {
        $this->entityManager->beginTransaction();
        $repository = $this->entityManager->getRepository('PartKeeprCronLoggerBundle:CronLogger');

        $cronJobs = $repository->findAll();

        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $output = new NullOutput();

        $minRunDate = new \DateTime();
        $minRunDate->sub(new \DateInterval('PT6H'));

        foreach ($cronJobs as $cronJob) {
            if ($minRunDate->getTimestamp() - $cronJob->getLastRunDate()->getTimestamp() < 0) {
                break;
            }

            $command = $cronJob->getCronjob();

            $input = new ArrayInput([
                'command' => $command,
            ]);

            $application->run($input, $output);
        }

        $this->entityManager->commit();
    }
}
