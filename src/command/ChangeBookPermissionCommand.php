<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Human;

final class ChangeBookPermissionCommand extends BaseSubCommand{
	use BookRequiredCommandTrait;

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_PERM);
		$this->registerArgument(0, new PermissionStringEnumArgument("permission"));
		$this->registerArgument(0, new PermissionStringArgument("permission"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		if(!is_null($book = $this->getBookInHand($sender))){
			assert($sender instanceof Human);
			$lore = $book->getLore();
			$lore[0] = $perm = $args["permission"];
			$sender->getInventory()->setItemInHand($book->setLore($lore));
			Command::broadcastCommandMessage($sender, "Execute permission has been successfully changed to \"$perm\".");
		}
	}
}
