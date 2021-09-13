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
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_DEFAULT), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_OP), [$operator, $op]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_RELOAD), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_NEW), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_SAVE), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_LOAD), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_PERM), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_EXEC), [$operator]);

		$everyone = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_USER);
		$everyone->addChild(EvalBookPermissionNames::EXECUTE_EVERYONE, true);
		$console = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_CONSOLE);
		$console->addChild(EvalBookPermissionNames::COMMAND_RELOAD, true);
	}
}