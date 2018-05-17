<?php

namespace PDA;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as T;

use PDA\PDAEntity;

class PDA extends PluginBase implements Listener{
	
	public $players = [];
    public $prefix = T::GRAY."[".T::YELLOW."PDA".T::GRAY."]";

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("PDA plugin by Amirgta1");
		Entity::registerEntity(PDAEntity::class, true);
    }

	public function onDeath(PlayerDeathEvent $event)
	{
		$player = $event->getPlayer();
		Entity::createEntity("PDAEntity", $player->getLevel(), new CompoundTag(), $player)->spawnToAll();
	}
}
