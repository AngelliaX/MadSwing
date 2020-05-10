<?php

namespace Tungsten\MadSwing;

use pocketmine\plugin\PluginBase;
use pocketmine\block\BlockFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use Tungsten\MadSwing\chair\Chair;
use Tungsten\MadSwing\chair\Knot;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\Player;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Skin;

use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;

use  pocketmine\nbt\JsonNbtParser;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
class MadSwing extends PluginBase implements Listener {


	public function onEnable(){
		#BlockFactory::registerBlock(new Slab(), true);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		Entity::registerEntity(Chair::class, true);
		Entity::registerEntity(Knot::class, true);
	}

	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
       
		switch(strtolower($command->getName())){
            case "test":      
               $this->command($sender);
               #$this->knot($sender);
               break;
        }

		return true;
	}
	public function knot(Player $sender){
		$entity = Entity::createEntity("Knot", $sender->getLevel(), Entity::createBaseNBT($sender, null, $sender->getYaw()));
		$entity->spawnToAll();
	}
	public function dataPacketRev(DataPacketReceiveEvent $event): void {
		return;
		$packet = $event->getPacket();
		if($packet instanceof PlayerInputPacket) {
			var_dump($packet);
		} elseif($packet instanceof InteractPacket) {
			var_dump($packet);
		}
	}
	public function onTapEntity(EntityDamageByEntityEvent $event){
		#var_dump($event->getEntity()->getName());
		if(!$event->getDamager() instanceof Player){
			return;
		}
		if($event->getEntity()->getName() == "Chair"){
			$event->setCancelled();
		}
		$this->setSitting($event->getDamager(), $event->getEntity(), $event->getEntity()->getId());
        $event->getDamager()->sendTip("sit rn 2");
	}
	public function onInteract(PlayerInteractEvent $event){
        return;
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(true){
            $eid = Entity::$entityCount;
            $this->setSitting($player, $block->asVector3(), $eid);
            $player->sendTip("sit rn");
        }
    }
    public function setSitting(Player $player, Vector3 $pos, int $id){
        $link = new EntityLink();
		$link->fromEntityUniqueId = $id;
		$link->type = 1;
		$link->toEntityUniqueId = $player->getId();
		$link->immediate = true;
		$player->getDataPropertyManager()->setVector3(56,new Vector3(0, 0, 0));
		if($player instanceof Player) {
			$pk = new SetActorLinkPacket();
			$pk->link = $link;
			$player->dataPacket($pk);
		}
    }

	public function command($sender){
	   $skintag = new CompoundTag("Skin", [
			    new StringTag("Name", "j khong duoc"),
				new ByteArrayTag("Data", file_get_contents("MadSwingPNG.json")),
				new ByteArrayTag("CapeData", ""),
				new StringTag("GeometryName", "geometry.madswing"),
				new ByteArrayTag("GeometryData", file_get_contents("MadSwing.json"))
			  ]);
	   $nbt = Entity::createBaseNBT($sender);
       $nbt->setTag(clone $skintag);

	   $npc = Entity::createEntity("Chair",$sender->getLevel(),$nbt);   	   
       $npc->spawnToAll();
	}
}