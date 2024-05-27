<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

final readonly class EvalBookPermissions{

	public const ROOT_OPERATOR = EvalBookPermissionNames::GROUP_OPERATOR;

	public static function registerCorePermissions() : void{
		$operator = DefaultPermissions::registerPermission(new Permission(self::ROOT_OPERATOR));
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::BYPASS_BOOK_SOFT_LIMIT), [$operator]);

		$console = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_CONSOLE);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND), [$operator, $console]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_RELOAD), [$operator, $console]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_PERM), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_NAME), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_GET), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_GIVE), [$operator, $console]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_EDIT), [$operator]);
	}
}
