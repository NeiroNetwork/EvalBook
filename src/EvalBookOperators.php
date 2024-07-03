<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\Main as EvalBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

final class EvalBookOperators{
	use SingletonTrait;

	private readonly Config $operators;

	public function __construct(){
		$this->operators = new Config(Path::join(EvalBook::getPlugin()->getDataFolder(), "allowlist.txt"), Config::ENUM);
	}

	public function reload() : void{
		$this->operators->reload();
	}

	/**
	 * @return string[]
	 */
	public function getNames() : array{
		return $this->operators->getAll(true);
	}

	public function exists(string $name) : bool{
		return $this->operators->exists($name, true);
	}

	/**
	 * @return Player[]
	 */
	public function getOnlineOperators() : array{
		$operators = [];
		foreach(Server::getInstance()->getOnlinePlayers() as $player){
			if($player->hasPermission(EvalBookPermissions::ROOT_OPERATOR)){
				$operators[] = $player;
			}
		}
		
		return $operators;
	}
}
