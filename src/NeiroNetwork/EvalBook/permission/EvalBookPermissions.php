<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;

abstract class EvalBookPermissions{

	public const ROOT_OPERATOR = EvalBookPermissionNames::GROUP_OPERATOR;
	public const ROOT_OP = DefaultPermissions::ROOT_OPERATOR;
	public const ROOT_EVERYONE = EvalBookPermissionNames::GROUP_EVERYONE;

	public static function registerPermissions() : void{
		$op = DefaultPermissions::registerPermission(new Permission(self::ROOT_OPERATOR));
		$user = DefaultPermissions::registerPermission(new Permission(self::ROOT_EVERYONE), [$op]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_EVALBOOK), [$op]);
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_CODEBOOK), [$op]);
		// TODO
		DefaultPermissions::registerPermission(new Permission(EvalBookPermissionNames::EXECUTE_CODEBOOK_OP), []);
	}
}