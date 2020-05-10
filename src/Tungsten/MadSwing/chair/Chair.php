<?php

namespace Tungsten\MadSwing\chair;

use pocketmine\entity\Human;

use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
class Chair extends Human{
    /** @var Entity $player */
	public $player = "";
    public $timer = 10;
    public $level;
    public function __construct(Level $level,CompoundTag $nbt){
		$this->level = $level;
		parent::__construct($level,$nbt);
	}
    public function entityBaseTick(int $tickDiff = 20) : bool{		
		  $this->setYawPitch();
		  $this->yaw = 0;
		  $this->pitch = 0;
		  #return true;
		  $force = 0.5;
		  $this->timer -= 1;
		  if($this->timer <= -10){
		  	$this->timer = 10;
		  }
		  if($this->timer <= -5){
		  	$this->motion->x = $force;
		    $this->motion->y = -$force;
		    #var_dump("4");
		    #-5,-6,-7,-8,-9
		  }else if($this->timer <= 0){
		  	$this->motion->x = -$force;
		    $this->motion->y = $force;
		    #var_dump("3");
		    #0,-1,-2,-3,-4
		  }else if($this->timer <= 5){
		  	 $this->motion->x = -$force;
		     $this->motion->y = -$force;
		    # var_dump("2");
		     #5,4,3,2,1
		  }else if($this->timer > 5){
		     $this->motion->x = $force;
		     $this->motion->y = $force;
		     #10,9,8,7,6
		    # var_dump("1");
		  }
		  $this->updateMovement();
		  return true;
	}
	public function setYawPitch(){
		$pk = new MovePlayerPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->yaw = 0;
		$pk->pitch = 0;
		$pk->position = $this->getPosition();
		#$pk->headYaw = $yaw;
		foreach ($this->level->getPlayers() as $player) {
			$player->dataPacket($pk);
		}
	}
	public function follow($target, float $xOffset = 0.0, float $yOffset = 0.0, float $zOffset = 0.0): void {
		$x = $target->x + $xOffset - $this->x;
		$y = $target->y + $yOffset - $this->y;
		$z = $target->z + $zOffset - $this->z;

		$xz_sq = $x * $x + $z * $z;
		$xz_modulus = sqrt($xz_sq);

		if($xz_sq < 1) {
			$this->motion->x = 0;
			$this->motion->z = 0;
		} else{
			$speed_factor = 1 * 0.15;
			$this->motion->x = $speed_factor * ($x / $xz_modulus);
			$this->motion->z = $speed_factor * ($z / $xz_modulus);
		}
		$this->yaw = rad2deg(atan2(-$x, $z));
		$this->pitch = rad2deg(-atan2($y, $xz_modulus));

		$this->move($this->motion->x, $this->motion->y, $this->motion->z);
	}
	
	public function getName(): string{
                return "Chair";
	}
	
	public function getShortName() :string{
		return "Chair";
	}
}