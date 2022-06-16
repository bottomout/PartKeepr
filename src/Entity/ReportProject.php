<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation\TargetService;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents one project and the quantity.
 *
 * @ORM\Entity
 * @TargetService("/api/project_report_projects")
 * @ExtModelName("PartKeepr.ProjectBundle.Entity.ReportProject")
 */
class ReportProject extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Report",inversedBy="reportProjects")
     *
     * @var Report
     */
    private $report;

    /**
     * The project the report refers to.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     * @Groups({"default"})
     * @Assert\NotNull()
     *
     * @var Project
     */
    private $project;

    /**
     * Specifies the amount this project should be reported.
     *
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     *
     * @var int
     */
    private $quantity;

    /**
     * @return mixed
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param mixed $report
     *
     * @return ReportProject
     */
    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return ReportProject
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
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
     *
     * @return ReportProject
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }
}
