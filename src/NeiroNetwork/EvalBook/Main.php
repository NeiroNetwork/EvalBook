<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Webmozart\PathUtil\Path;

class Main extends PluginBase{

	private static self $instance;

	public static function getInstance() : self{
		return self::$instance;
	}

	private Config $operators;

	protected function onEnable() : void{
		self::$instance = $this;
		$this->preparePermissions();
		$this->operators = new Config(Path::join($this->getDataFolder(), "whitelist.txt"), Config::ENUM);
	}

	private function preparePermissions() : void{
		$root = DefaultPermissions::registerPermission(new Permission(EvalBookPermissions::ROOT_OPERATOR));
		DefaultPermissions::registerPermission(new Permission("evalbook.test"), [$root]);
	}

	public function getOperators() : Config{
		return $this->operators;
	}
}