<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\LibEvalBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;
use pocketmine\entity\Human;
use pocketmine\item\WrittenBook;

final class RevertBookSignatureCommand extends BaseSubCommand{
	use BookRequiredCommandTrait;

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_EDIT);
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		if(!is_null($book = $this->getBookInHand($sender))){
			assert($sender instanceof Human);
			if($book instanceof WrittenBook){
				$sender->getInventory()->setItemInHand(LibEvalBook::toWritableBook($book));
				$sender->sendMessage("The book has been reverted to a writable book.");
			}else{
				$sender->sendMessage("The book is not signed.");
			}
		}
	}
}
