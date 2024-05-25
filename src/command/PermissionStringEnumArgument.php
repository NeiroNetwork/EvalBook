<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\args\StringEnumArgument;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

final class PermissionStringEnumArgument extends StringEnumArgument{

	protected const VALUES = [
		"default" => EvalBookPermissions::ROOT_OPERATOR,
		"op" => DefaultPermissions::ROOT_OPERATOR,
		"everyone" => DefaultPermissions::ROOT_USER,
	];

	public function getTypeName() : string{
		return "string";
	}

	public function getEnumName() : string{
		return "permission";
	}

	public function parse(string $argument, CommandSender $sender) : mixed{
		return $this->getValue($argument);
	}
}
