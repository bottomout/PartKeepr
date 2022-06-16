<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;

/**
 * Holds a storage location image.
 *
 * @ORM\Entity
 * @ExtModelName("PartKeepr.StorageLocationBundle.Entity.StorageLocationImage")
 **/
class StorageLocationImage extends Image
{
    /**
     * The storage location object.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\StorageLocation",inversedBy="image")
     *
     * @var StorageLocation
     */
    private $storageLocation = null;

    /**
     * Creates a new storage location image instance.
     */
    public function __construct()
    {
        parent::__construct(Image::IMAGE_STORAGELOCATION);
    }

    /**
     * Sets the storage location.
     *
     * @param StorageLocation $storageLocation The storage location to set
     */
    public function setStorageLocation(StorageLocation $storageLocation)
    {
        $this->storageLocation = $storageLocation;
    }

    /**
     * Returns the storage location.
     *
     * @return StorageLocation the storage location
     */
    public function getStorageLocation()
    {
        return $this->storageLocation;
    }
}
