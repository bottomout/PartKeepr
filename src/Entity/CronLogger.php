<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;

/**
 * Holds a project attachment.
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="cronjob", columns={"cronjob"})})
 * @ORM\Entity
 * @ExtModelName("PartKeepr.CronLoggerBundle.Entity.CronLogger")
 **/
class CronLogger extends BaseEntity
{
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $lastRunDate;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $cronjob;

    /**
     * Sets the last run date and time for this entry.
     *
     * @param \DateTime $date The date and time
     */
    public function setLastRunDate(\DateTime $date)
    {
        $this->lastRunDate = $date;
    }

    /**
     * Returns the date and time for this entry.
     *
     * @return \DateTime the date and time for this entry
     */
    public function getLastRunDate()
    {
        return $this->lastRunDate;
    }

    /**
     * Sets the cronjob for this entry.
     *
     * @param string $cronjob the title for this entry
     */
    public function setCronjob($cronjob)
    {
        $this->cronjob = $cronjob;
    }

    /**
     * Returns the cronjob for this entry.
     *
     * @return string the title
     */
    public function getCronjob()
    {
        return $this->cronjob;
    }
}
