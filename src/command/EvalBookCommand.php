<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseCommand;
use NeiroNetwork\EvalBook\command\sub\CustomNameCommand;
use NeiroNetwork\EvalBook\command\sub\NewCommand;
use NeiroNetwork\EvalBook\command\sub\PermCommand;
use NeiroNetwork\EvalBook\command\sub\ReloadCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;

class EvalBookCommand extends BaseCommand{

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND);

		$subCommands = [
			new ReloadCommand($this->plugin, "reload", "Reload permitted operators configuration file"),
			new NewCommand($this->plugin, "new", "Get a new eval book", ["get", "give"]),
			new PermCommand($this->plugin, "perm", "Set the book's execution permission", ["permission"]),
			new CustomNameCommand($this->plugin, "customname", "Rename the book", ["name"]),
		];
		foreach($subCommands as $subCommand){
			$this->registerSubCommand($subCommand);
		}
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$this->sendUsage();
	}

	// TODO: REMOVE THIS 💩 PATCH FOR PM5
	public function getPermission(){
		return [EvalBookPermissionNames::COMMAND];
	}
}
