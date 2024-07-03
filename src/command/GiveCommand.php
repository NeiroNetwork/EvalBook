<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\args\TargetPlayerArgument;
use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\LibEvalBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;
use pocketmine\inventory\InventoryHolder;

final class GiveCommand extends BaseSubCommand{

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_GIVE);
		$this->registerArgument(0, new TargetPlayerArgument(true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$target = match($args["player"] ?? null){
			null, "@s", "@p" => $sender,
			default => $sender->getServer()->getPlayerExact($args["player"]),
		};
		if(is_null($target)){
			$sender->sendMessage("§cPlayer \"{$args["player"]}\" not found");
			return;
		}
		$book = LibEvalBook::createEmptyBook();
		if($target instanceof InventoryHolder && $target->getInventory()->canAddItem($book)){
			$target->getInventory()->addItem($book);
		}
	}
}
