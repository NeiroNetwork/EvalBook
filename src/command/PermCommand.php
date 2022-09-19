<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\args\StringEnumArgument;
use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PermCommand extends BaseSubCommand{
	use BookInHandCommandTrait;

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_PERM);
		$this->registerArgument(0, new class("permission") extends StringEnumArgument{
			protected const VALUES = ["default" => "default", "op" => "op", "everyone" => "everyone"];
			public function getTypeName() : string{ return "string"; }
			public function parse(string $argument, CommandSender $sender) : mixed{ return $this->getValue($argument); }
		});
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		if(is_null($book = $this->getBook($sender))) return;
		$sender->getInventory()->setItemInHand($book->setLore([$perm = $args["permission"]]));
		Command::broadcastCommandMessage($sender, "Execute permission has been successfully changed to $perm.");
	}
}
