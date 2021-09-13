<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
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

		EvalBookPermissions::registerPermissions();

		$this->operators = new Config(Path::join($this->getDataFolder(), "whitelist.txt"), Config::ENUM);

		$console = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_CONSOLE);
		$console->addChild(EvalBookPermissions::ROOT_OPERATOR, true);

		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}
}