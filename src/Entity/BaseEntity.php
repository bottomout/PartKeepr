<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ExtModelName("PartKeepr.CoreBundle.Entity.BaseEntity")
 */
abstract class BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * Returns the ID of this object.
     *
     * @param none
     *
     * @return int The ID of this object
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return get_class($this)." #".$this->getId();
    }
}
