<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

final class EvalBookOperators{
	use SingletonTrait;

	private Config $operators;

	public function __construct(){
		$dataFolder = Server::getInstance()->getPluginManager()->getPlugin("EvalBook")->getDataFolder();    // FIXME
		$this->operators = new Config(Path::join($dataFolder, "allowlist.txt"), Config::ENUM);
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
}
