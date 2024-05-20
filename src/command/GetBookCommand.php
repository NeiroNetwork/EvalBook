<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\LibEvalBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;
use pocketmine\inventory\InventoryHolder;

final class GetBookCommand extends BaseSubCommand{

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$book = LibEvalBook::createEmptyBook();
		if($sender instanceof InventoryHolder && $sender->getInventory()->canAddItem($book)){
			$sender->getInventory()->addItem($book);
		}
	}

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_GET);
	}
}
