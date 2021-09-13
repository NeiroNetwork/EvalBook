<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use NeiroNetwork\EvalBook\item\EvalBook;
use NeiroNetwork\EvalBook\item\ExecutableBook;
use NeiroNetwork\EvalBook\Main;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\entity\Human;
use pocketmine\inventory\InventoryHolder;
use pocketmine\permission\PermissionManager;
use pocketmine\Server;

class EvalBookCommand extends Command{

	public function __construct(string $name){
		parent::__construct(
			$name,
			"EvalBook Command",
			"/evalbook <reload|new|save|load|perm|exec>"
		);
		$this->setPermission(implode(";", [
			EvalBookPermissionNames::COMMAND_RELOAD,
			EvalBookPermissionNames::COMMAND_NEW,
			EvalBookPermissionNames::COMMAND_SAVE,
			EvalBookPermissionNames::COMMAND_LOAD,
			EvalBookPermissionNames::COMMAND_PERM,
			EvalBookPermissionNames::COMMAND_EXEC,
		]));
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 1){
			switch(strtolower($args[0])){
				case "reload":
					if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_RELOAD)){
						$operators = Main::getInstance()->getOperators();
						foreach($operators->getAll(true) as $name){
							Server::getInstance()->getPlayerExact($name)?->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, false);
						}
						$operators->reload();
						foreach($operators->getAll(true) as $name){
							Server::getInstance()->getPlayerExact($name)?->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
						}
						Command::broadcastCommandMessage($sender, "Configuration file has been reloaded.");
					}
					return true;

				case "new":
					if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_NEW)){
						if($sender instanceof InventoryHolder && $sender->getInventory()->canAddItem($item = EvalBook::new())){
							$sender->getInventory()->addItem($item);
						}
					}
					return true;

				case "save":
					if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_SAVE)){
						// TODO: implement
					}
					return true;

				case "load":
					if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_LOAD)){
						// TODO: implement
					}
					return true;

				case "exec":
				case "execute":
				case "run":
				case "eval":
					// TODO
					return true;
			}
		}elseif(count($args) === 2){
			switch(strtolower($args[0])){
				case "perm":
				case "permission":
					if($this->testPermission($sender, EvalBookPermissionNames::COMMAND_PERM)){
						$permission = strtolower($args[1]);
						if(PermissionManager::getInstance()->getPermission(EvalBookPermissionNames::EVALBOOK_EXECUTE . ".$permission") === null){
							Command::broadcastCommandMessage($sender, "Permission \"$permission\" does not exist.");
							return true;
						}
						if(!$sender instanceof Human || !ExecutableBook::equals($item = $sender->getInventory()->getItemInHand())){
							Command::broadcastCommandMessage($sender, "Couldn't find executable book.");
							return true;
						}
						$sender->getInventory()->setItemInHand($item->setLore([$permission]));
						Command::broadcastCommandMessage($sender, "Execute permissions have been successfully changed.");
					}
					return true;
			}
		}

		throw new InvalidCommandSyntaxException();
	}
}