<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use NeiroNetwork\EvalBook\LibEvalBook;
use pocketmine\command\CommandSender;
use pocketmine\entity\Human;
use pocketmine\item\WritableBookBase;

trait BookRequiredCommandTrait{

	protected function getBookInHand(CommandSender $sender) : ?WritableBookBase{
		if(!$sender instanceof Human){
			$sender->sendMessage("§cRun this command in-game!");
			return null;
		}

		$item = $sender->getInventory()->getItemInHand();
		if(!LibEvalBook::isEvalBook($item)){
			$sender->sendMessage("§cHold the book in your hand and then run the command!");
			return null;
		}

		return $item;
	}
}
