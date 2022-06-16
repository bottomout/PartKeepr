<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation\TargetService;

/**
 * Holds a project attachment.
 *
 * @ORM\Entity
 * @TargetService("/api/project_attachments")
 * @ExtModelName("PartKeepr.ProjectBundle.Entity.ProjectAttachment")
 **/
class ProjectAttachment extends UploadedFile
{
    /**
     * Creates a new project attachment.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('ProjectAttachment');
    }

    /**
     * The project object.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="attachments")
     *
     * @var Project
     */
    private $project = null;

    /**
     * Sets the project.
     *
     * @param Project $project The project to set
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;
    }

    /**
     * Returns the roject.
     *
     * @return Project the project
     */
    public function getProject()
    {
        return $this->project;
    }
}
