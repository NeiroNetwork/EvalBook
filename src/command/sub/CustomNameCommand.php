<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command\sub;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CustomNameCommand extends BaseSubCommand{
	use BookInHandCommandTrait;

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_CUSTOM_NAME);
		$this->registerArgument(0, new TextArgument("name"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		if(is_null($book = $this->getBook($sender))) return;
		$sender->getInventory()->setItemInHand($book->setCustomName($args["name"]));
		Command::broadcastCommandMessage($sender, "Custom name of the book has been changed.");
	}
}
