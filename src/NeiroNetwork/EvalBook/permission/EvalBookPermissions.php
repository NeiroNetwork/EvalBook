<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

abstract class EvalBookPermissions{

	public const ROOT_OPERATOR = EvalBookPermissionNames::GROUP_OPERATOR;

	public static function registerPermissions() : void{
		$operator = DefaultPermissions::registerPermission(new Permission(self::ROOT_OPERATOR));
		$op = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
		$console = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_CONSOLE);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_DEFAULT), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_OP), [$operator, $op]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_RELOAD), [$operator, $console]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_NEW), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_PERM), [$operator]);

		$everyone = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_USER);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_EVERYONE), [$everyone]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_EXEC), [$operator, $everyone]);
	}
}