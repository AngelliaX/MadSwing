<?php

namespace Tungsten\MadSwing\chair;

use pocketmine\entity\Entity;

use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

class Knot extends Entity{
    public const NETWORK_ID = self::LEASH_KNOT;
    public function __construct(Level $level,CompoundTag $nbt){
		var_dump("oka");
		#$this->setNameTag("Knot");
	}
}