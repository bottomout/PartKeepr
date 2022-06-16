<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Represents a project run part.
 *
 * @ORM\Entity
 * @ExtModelName("PartKeepr.ProjectBundle.Entity.ProjectRunPart")
 */
class ProjectRunPart extends BaseEntity
{
    /**
     * Stores the project run.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjectRun",inversedBy="parts")
     * @Groups({"default"})
     *
     * @var ProjectRun
     */
    private $projectRun;

    /**
     * Stores the part used in a production run.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Part")
     * @Groups({"default"})
     *
     * @var Part
     */
    private $part;

    /**
     * Stores the quantity of a production run.
     *
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     *
     * @var int
     */
    private $quantity;

    /**
     * Specifies the lot number.
     *
     * @ORM\Column(type="text")
     * @Groups({"default"})
     *
     * @var string
     */
    private $lotNumber;

    /**
     * @return string
     */
    public function getLotNumber()
    {
        return $this->lotNumber;
    }

    /**
     * @param string $lotNumber
     */
    public function setLotNumber($lotNumber)
    {
        $this->lotNumber = $lotNumber;
    }

    /**
     * @return ProjectRun
     */
    public function getProjectRun()
    {
        return $this->projectRun;
    }

    /**
     * @param ProjectRun $projectRun
     */
    public function setProjectRun($projectRun)
    {
        $this->projectRun = $projectRun;
    }

    /**
     * @return Part
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * @param Part $part
     */
    public function setPart($part)
    {
        $this->part = $part;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function __toString()
    {
        // @todo i18n
        return sprintf(
            "Used in project run for project %s on %s",
            $this->getProjectRun()->getProject()->getName(),
            $this->getProjectRun()->getRunDateTime()->format("Y-m-d H:i:s")
        )." / ".parent::__toString();
    }
}