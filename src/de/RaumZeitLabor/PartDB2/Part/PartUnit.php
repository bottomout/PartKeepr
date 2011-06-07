<?php
namespace de\RaumZeitLabor\PartDB2\Part;
declare(encoding = 'UTF-8');

use de\RaumZeitLabor\PartDB2\PartDB2,
	de\RaumZeitLabor\PartDB2\Util\Exceptions\OutOfRangeException;


/** @Entity **/
class PartUnit {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	private $id;
	
	/**
	 * Defines the name of the unit
	 * @Column
	 * @var string
	 */
	private $name;
	
	/**
	 * Defines the short name of the unit
	 * @Column
	 * @var string
	 */
	private $shortName;
	
	/**
	 * Defines if the unit is default or not.
	 * 
	 * @Column(type="boolean")
	 * @var boolean
	 */
	private $is_default;
	
	/**
	 * Creates a new part unit.
	 * 
	 * Sets the default to false.
	 */
	public function __construct () {
		$this->setDefault(false);
	}
	
	/**
	 * Sets the name for this unit
	 * @param string $name The name for this unit
	 * @return nothing
	 */
	public function setName ($name) {
		$this->name = $name;
	}
	
	/**
	 * Returns the name for this unit
	 * @param none
	 * @return string The name for this unit
	 */
	public function getName () {
		return $this->name;
	}
	
	/**
	 * Sets the short name for this unit.
	 * 
	 * Short names are used for list views (e.g. if your unit name is "metres", your short name could be "m")
	 * @param string $shortName The short name
	 * @return nothing
	 */
	public function setShortName ($shortName) {
		$this->shortName = $shortName;
	}
	
	/**
	 * Returns the short name for this unit
	 * @param none
	 * @return string The short name for this unit
	 */
	public function getShortName () {
		return $this->shortName;
	}
	
	/**
	 * Returns the ID for this object.
	 * @param none
	 * @return int The ID for this object
	 */
	public function getId () {
		return $this->id;
	}
	
	/**
	 * Defines if the unit is default or not.
	 * @param boolean $default True if the unit is default, false otherwise
	 */
	public function setDefault ($default) {
		$this->is_default = (bool)$default;
	}
	
	/**
	 * Returns if the unit is default or not
	 * @param none
	 * @return boolean True if the unit is default, false for not
	 */
	public function getDefault () {
		return $this->is_default;
	}
	
	/**
	 * Serializes the object and returns it as array, suitable
	 * to process via json_encode.
	 * @param none
	 * @return array An array containing the object information
	 */
	public function serialize () {
		return array(
					"id" => $this->getId(),
					"name" => $this->getName(),
					"shortName" => $this->getShortName(),
					"default" => $this->getDefault()
		);
	}
}
	