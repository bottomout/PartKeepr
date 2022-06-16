<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Holds a part attachment.
 *
 * @ORM\Entity
 * @ExtModelName("PartKeepr.PartBundle.Entity.PartAttachment")
 **/
class PartAttachment extends UploadedFile
{
    /**
     * Defines if the attachment is an image.
     *
     * @ORM\Column(type="boolean",nullable=true)
     * @Groups({"default"})
     *
     * @var bool
     */
    private $isImage;

    /**
     * Creates a new part attachment.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('PartAttachment');
        $this->isImage = null;
    }

    /**
     * The part object.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Part", inversedBy="attachments")
     *
     * @var Part
     */
    private $part = null;

    /**
     * Sets the part.
     *
     * @param Part $part The part to set
     */
    public function setPart(Part $part = null)
    {
        $this->part = $part;
    }

    /**
     * Returns the part.
     *
     * @return Part the part
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Returns if the attachment is an image or not.
     *
     * @return true if the attachment is an image, false otherwise
     */
    public function isImage()
    {
        return $this->isImage;
    }

    /**
     * Sets if the attachment is an image.
     *
     * @param $image
     */
    public function setImage($image)
    {
        $this->isImage = $image;
    }
}