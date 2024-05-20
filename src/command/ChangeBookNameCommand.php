<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;
use pocketmine\entity\Human;

class ChangeBookNameCommand extends BaseSubCommand{
	use BookRequiredCommandTrait;

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		if(!is_null($book = $this->getBookInHand($sender))){
			assert($sender instanceof Human);
			$sender->getInventory()->setItemInHand($book->setCustomName($args["name"]));
			$sender->sendMessage("Custom name of the book has been changed.");
		}
	}

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_NAME);
		$this->registerArgument(0, new TextArgument("name"));
	}
}
