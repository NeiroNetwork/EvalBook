<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command\sub;

use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\item\ExecutableBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;
use pocketmine\inventory\InventoryHolder;

class NewCommand extends BaseSubCommand{

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_NEW);
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$book = ExecutableBook::makeWritable();
		if($sender instanceof InventoryHolder && $sender->getInventory()->canAddItem($book)){
			$sender->getInventory()->addItem($book);
		}
	}
}
