<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation\IgnoreIds;
use App\Annotation\TargetService;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Represents a user preference entry.
 *
 * User preferences are a simple key => value mechanism, where the developer can
 * specify the key and value himself.
 *
 * Note that values are stored internally as serialized PHP values to keep their type.
 *
 * @ORM\Entity
 * @TargetService(uri="/api/user_preferences")
 * @IgnoreIds()
 * @ExtModelName("PartKeepr.AuthBundle.Entity.UserPreference")
 **/
class UserPreference
{
    /**
     * Defines the key of the user preference.
     *
     * @ORM\Column(type="string",length=255)
     * @ORM\Id()
     *
     * @Groups({"default"})
     *
     * @var string
     */
    private $preferenceKey;

    /**
     * Defines the value. Note that the value is internally stored as a serialized string.
     *
     * @ORM\Column(type="text")
     *
     * @Groups({"default"})
     *
     * @var mixed
     */
    private $preferenceValue;

    /**
     * Defines the user.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\Id()
     *
     * @var App\Entity\User
     */
    private $user;

    /**
     * Sets the user for this entry.
     *
     * @param App\Entity\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the user associated with this entry.
     *
     * @return App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the key for this user preference.
     *
     * @param string $key The key name
     */
    public function setPreferenceKey($key)
    {
        $this->preferenceKey = $key;
    }

    /**
     * Returns the key of this entry.
     *
     * @return string
     */
    public function getPreferenceKey()
    {
        return $this->preferenceKey;
    }

    /**
     * Sets the value for this entry.
     *
     * @param mixed $value
     */
    public function setPreferenceValue($value)
    {
        $this->preferenceValue = serialize($value);
    }

    /**
     * Returns the value for this entry.
     *
     * @return mixed The value
     */
    public function getPreferenceValue()
    {
        return unserialize($this->preferenceValue);
    }
}
