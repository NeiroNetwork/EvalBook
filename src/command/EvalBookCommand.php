<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;

class EvalBookCommand extends BaseCommand{

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND);
		$this->registerSubCommand(new ReloadCommand("reload", "Reload permitted operators configuration file"));
		$this->registerSubCommand(new NewCommand("new", "Get a new eval book", ["get", "give"]));
		$this->registerSubCommand(new PermCommand("perm", "Set the book's execution permission", ["permission"]));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$this->sendUsage();
	}
}
