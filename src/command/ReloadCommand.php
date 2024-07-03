<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseSubCommand;
use NeiroNetwork\EvalBook\EvalBookOperators;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

final class ReloadCommand extends BaseSubCommand{

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND_RELOAD);
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$server = $sender->getServer();

		foreach(EvalBookOperators::getInstance()->getNames() as $name){
			$server->getPlayerExact($name)?->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, false);
		}

		EvalBookOperators::getInstance()->reload();

		foreach(EvalBookOperators::getInstance()->getNames() as $name){
			$server->getPlayerExact($name)?->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
		}

		Command::broadcastCommandMessage($sender, "Configuration file has been reloaded.");
	}
}
