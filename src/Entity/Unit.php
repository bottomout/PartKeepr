<?php

namespace App\Entity;

use App\Annotation\ExtModelName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation\TargetService;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This object represents an unit. Units can be: Volt, Hertz etc.
 *
 * @ORM\Entity
 * @TargetService(uri="/api/units")
 * @ExtModelName("PartKeepr.UnitBundle.Entity.Unit")
 **/
class Unit extends BaseEntity
{
    /**
     * The name of the unit (e.g. Volts, Ampere, Farad, Metres).
     *
     * @ORM\Column(type="string")
     * @Groups({"default"})
     * @Assert\Type(type="string")
     * @Assert\NotBlank(message="unit.name.not_blank")
     *
     * @var string
     */
    private $name;

    /**
     * The symbol of the unit (e.g. V, A, F, m).
     *
     * @ORM\Column(type="string")
     * @Groups({"default"})
     * @Assert\Type(type="string")
     * @Assert\NotBlank(message="unit.symbol.not_blank")
     *
     * @var string
     */
    private $symbol;

    /**
     * Defines the allowed SiPrefixes for this parameter unit.
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\SiPrefix")
     * @ORM\JoinTable(name="UnitSiPrefixes",
     *            joinColumns={@ORM\JoinColumn(name="unit_id", referencedColumnName="id")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="siprefix_id", referencedColumnName="id")}
     *            )
     * @Groups({"default"})
     * @Assert\All({
     *      @Assert\Type(type="App\Entity\SiPrefix")
     * })
     *
     * @var ArrayCollection
     */
    private $prefixes;

    /**
     * Creates a new Unit.
     */
    public function __construct()
    {
        $this->prefixes = new ArrayCollection();
    }

    /**
     * Sets the name for this unit.
     *
     * @param string $name the name for this unit
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the name for this unit.
     *
     * @return string The unit name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the symbol for this unit.
     *
     * @param string $symbol The symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * Returns the symbol for this unit.
     *
     * @return string The symbol
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Returns the si-prefix list for this unit.
     *
     * @return ArrayCollection An array of SiPrefix objects
     */
    public function getPrefixes()
    {
        return $this->prefixes->getValues();
    }

    /**
     * Adds a prefix.
     *
     * @param SiPrefix
     */
    public function addPrefix($prefix)
    {
        $this->prefixes->add($prefix);
    }

    /**
     * Adds a prefix.
     *
     * @param SiPrefix
     */
    public function removePrefix($prefix)
    {
        $this->prefixes->removeElement($prefix);
    }
}
