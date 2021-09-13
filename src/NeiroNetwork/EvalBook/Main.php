<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\command\EvalBookCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Webmozart\PathUtil\Path;

class Main extends PluginBase{

	private static self $instance;
	private Config $operators;

	public static function getInstance() : self{
		return self::$instance;
	}

	public function getOperators() : Config{
		return $this->operators;
	}

	protected function onEnable() : void{
		self::$instance = $this;
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

		EvalBookPermissions::registerPermissions();

		$this->operators = new Config(Path::join($this->getDataFolder(), "allowlist.txt"), Config::ENUM);

		$this->getServer()->getCommandMap()->registerAll($this->getName(), [new EvalBookCommand("evalbook")]);
	}
}