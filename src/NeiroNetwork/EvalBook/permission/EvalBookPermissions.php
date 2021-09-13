<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;

abstract class EvalBookPermissions extends DefaultPermissions{

	public const ROOT_CONSOLE = EvalBookPermissionNames::GROUP_OPERATOR;
	public const ROOT_OPERATOR = EvalBookPermissionNames::GROUP_OPERATOR;
	public const ROOT_USER = EvalBookPermissionNames::GROUP_EVERYONE;

	public static function registerCorePermissions() : void{
		$root = self::registerPermission(new Permission(self::ROOT_OPERATOR));
		$everyone = self::registerPermission(new Permission(self::ROOT_USER), [$root]);
		self::registerPermission(new Permission("evalbook.test"), [$root]);
		self::registerPermission(new Permission("evalbook.test.everyone"), [$everyone]);
	}
}