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

		$this->registerSubCommand(new ReloadCommand("reload", "Reload permitted operators configuration file"));
		$this->registerSubCommand(new NewCommand("new", "Get a new eval book", ["get", "give"]));
		$this->registerSubCommand(new PermCommand("perm", "Set the book's execution permission", ["permission"]));
		$this->registerSubCommand(new CustomNameCommand("customname", "Rename the book", ["name"]));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$this->sendUsage();
	}

	// TODO: REMOVE THIS 💩
	public function getPermission(){
		return [EvalBookPermissionNames::COMMAND];
	}
}
