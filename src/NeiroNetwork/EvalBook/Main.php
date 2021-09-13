<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	protected function onEnable() : void{
		$this->preparePermissions();
	}

	private function preparePermissions() : void{
		$root = DefaultPermissions::registerPermission(new Permission(EvalBookPermissions::ROOT_OPERATOR));
		DefaultPermissions::registerPermission(new Permission("evalbook.test"), [$root]);
	}
}