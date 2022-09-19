<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use NeiroNetwork\EvalBook\item\ExecutableBook;
use pocketmine\command\CommandSender;
use pocketmine\entity\Human;
use pocketmine\item\WritableBookBase;

trait BookInHandCommandTrait{

	protected function getBook(CommandSender $sender) : ?WritableBookBase{
		if(!$sender instanceof Human){
			$sender->sendMessage("§cRun this command in-game!");
			return null;
		}

		$item = $sender->getInventory()->getItemInHand();
		if(!ExecutableBook::validItem($item)){
			$sender->sendMessage("§cHold the book in your hand and then run the command!");
			return null;
		}

		return $item;
	}
}
