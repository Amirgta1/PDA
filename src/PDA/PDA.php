<?php
namespace PDA;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\nbt\tag\{ListTag, FloatTag, DoubleTag, CompoundTag, StringTag};
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
		if (!is_dir($this->getDataFolder())){
			mkdir($this->getDataFolder());
		}
		$this->saveDefaultConfig();
		Entity::registerEntity(PDAEntity::class, true);
	}

	public function onDeath(PlayerDeathEvent $e){
		$player = $e->getPlayer();
		$nbt = new CompoundTag("", [
		    new ListTag("Pos", [
			new DoubleTag("", $player->getX()),
			new DoubleTag("", $player->getY() - 1),
			new DoubleTag("", $player->getZ())
		    ]),
		    new ListTag("Motion", [
			new DoubleTag("", 0),
			new DoubleTag("", 0),
			new DoubleTag("", 0)
		    ]),
		    new ListTag("Rotation", [
			new FloatTag("", 2),
			new FloatTag("", 2)
		    ])
		]);
		$nbt->setTag($player->namedtag->getTag("Skin"));
		$npc = new PDAEntity($player->getLevel(), $nbt);
		$npc->getDataPropertyManager()->setBlockPos(PDAEntity::DATA_PLAYER_BED_POSITION, new Vector3($player->getX(), $player->getY(), $player->getZ()));
		$npc->setPlayerFlag(PDAEntity::DATA_PLAYER_FLAG_SLEEP, true);
		$npc->setNameTag("[Dead]" .$player->getName(). "");
		$npc->setNameTagAlwaysVisible(false);
		$npc->spawnToAll();
	}
}
