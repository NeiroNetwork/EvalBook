<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\args\StringEnumArgument;
use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\item\ExecutableBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Human;

class PermCommand extends BaseSubCommand{

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_PERM);
		$this->registerArgument(0, new class("permission") extends StringEnumArgument{
			protected const VALUES = ["default" => "default", "op" => "op", "everyone" => "everyone"];
			public function getTypeName() : string{ return "string"; }
			public function parse(string $argument, CommandSender $sender) : mixed{ return $this->getValue($argument); }
		});
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		if(!$sender instanceof Human){
			$sender->sendMessage("§cRun this command in-game!");
			return;
		}

		$item = $sender->getInventory()->getItemInHand();
		if(!ExecutableBook::validItem($item)){
			$sender->sendMessage("§cHold the book in your hand and then run the command!");
			return;
		}

		$perm = $args["permission"];
		$item->setLore([$perm]);
		Command::broadcastCommandMessage($sender, "Execute permission has been successfully changed to $perm.");
	}
}
