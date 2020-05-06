<?php

namespace Tungsten\MadSwing\chair;

use pocketmine\item\Item;
use pocketmine\block\Fallable;

class Slab extends Fallable {

	protected $id = 158;
	private $name = "Chair";
	private $toolType = \pocketmine\block\BlockToolType::TYPE_SHOVEL;

	public function __construct() {
		parent::__construct($this->id, 0, $this->name);
	}

	public function getHardness() : float{
		return 0.5;
	}

	public function getToolType() : int{
		return $this->toolType;
	}

	public function getName() : string{
		return $this->name;
	}
}