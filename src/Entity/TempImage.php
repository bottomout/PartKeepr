<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a temporary image. Temporary images are used when
 * a user uploaded an image, but not attached it to an entity.
 *
 * @ORM\Entity
 * @ExtModelName("PartKeepr.ImageBundle.Entity.TempImage")
 */
class TempImage extends Image
{
    public function __construct()
    {
        parent::__construct(Image::IMAGE_TEMP);
    }
}
