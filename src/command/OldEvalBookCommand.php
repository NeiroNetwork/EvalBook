<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use NeiroNetwork\EvalBook\item\WritableExecutableBook;
use NeiroNetwork\EvalBook\item\ExecutableBook;
use NeiroNetwork\EvalBook\Main;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\entity\Human;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\WritableBookBase;
use pocketmine\player\Player;

class OldEvalBookCommand extends Command{

	public function __construct(string $name){
		parent::__construct(
			$name,
			"EvalBook Command",
			"/evalbook <reload|new|perm|exec|customname>"
		);
		$this->setPermission(implode(";", [
			EvalBookPermissionNames::COMMAND_RELOAD,
			EvalBookPermissionNames::COMMAND_NEW,
			EvalBookPermissionNames::COMMAND_PERM,
			EvalBookPermissionNames::COMMAND_EXEC,
			EvalBookPermissionNames::COMMAND_CUSTOM_NAME,
		]));
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		switch(strtolower(array_shift($args) ?? "")){
			case "reload":
				if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_RELOAD)){
					$operators = Main::getInstance()->getOperators();
					foreach($operators->getAll(true) as $name){
						$sender->getServer()->getPlayerExact($name)?->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, false);
					}
					$operators->reload();
					foreach($operators->getAll(true) as $name){
						$sender->getServer()->getPlayerExact($name)?->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
					}
					Command::broadcastCommandMessage($sender, "Configuration file has been reloaded.");
				}
				return true;

			case "new":
			case "get":
			case "give":
				if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_NEW)){
					if($sender instanceof InventoryHolder && $sender->getInventory()->canAddItem($item = WritableExecutableBook::new())){
						$sender->getInventory()->addItem($item);
					}
				}
				return true;

			case "exec":
			case "execute":
			case "run":
			case "eval":
				if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_EXEC)
					&& ($item = $this->checkItem($sender))
					&& $this->testPermission($sender, ExecutableBook::getPermission($item)->getName())
				) ExecutableBook::execute($item, $sender);
				return true;

			case "perm":
			case "permission":
				if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_PERM)){
					if(ExecutableBook::getPermission($permission = strtolower(array_shift($args) ?? "")) === null){
						$sender->sendMessage("Permission \"$permission\" does not exist.");
						return true;
					}
					if(!($item = $this->checkItem($sender))){
						return true;
					}
					/** @var Player $sender */
					$sender->getInventory()->setItemInHand($item->setLore([$permission]));
					Command::broadcastCommandMessage($sender, "Execute permission has been successfully changed to $permission.");
				}
				return true;

			case "customname":
			case "name":
				if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_CUSTOM_NAME) && ($item = $this->checkItem($sender))){
					if(empty($customName = trim(implode(" ", $args)))){
						$sender->sendMessage("Custom name must not be empty.");
						return true;
					}
					/** @var Player $sender */
					$sender->getInventory()->setItemInHand($item->setCustomName($customName));
					Command::broadcastCommandMessage($sender, "Custom name of the book has been changed.");
				}
				return true;
		}

		throw new InvalidCommandSyntaxException();
	}

	private function checkItem(CommandSender $sender) : ?WritableBookBase{
		if(!$sender instanceof Human || !ExecutableBook::isExecutableBook($item = $sender->getInventory()->getItemInHand())){
			$sender->sendMessage("Couldn't find executable book.");
			return null;
		}
		return $item;
	}
}
