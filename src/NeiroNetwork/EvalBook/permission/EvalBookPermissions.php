<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

abstract class EvalBookPermissions{

	public const ROOT_OPERATOR = EvalBookPermissionNames::GROUP_OPERATOR;
	public const ROOT_EVERYONE = EvalBookPermissionNames::GROUP_EVERYONE;

	public static function registerPermissions() : void{
		$operator = DefaultPermissions::registerPermission(new Permission(self::ROOT_OPERATOR));
		$everyone = DefaultPermissions::registerPermission(new Permission(self::ROOT_EVERYONE), [$operator]);
		$op = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_EVALBOOK), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_CODEBOOK), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_CODEBOOK_OP), [$op]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_COODEBOOK_EVERYONE), [$everyone]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_RELOAD), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_NEW), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_SAVE), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_LOAD), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_PERM), [$operator]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::COMMAND_EXEC), [$operator]);
	}
}