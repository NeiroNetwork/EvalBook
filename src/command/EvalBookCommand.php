<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

final class EvalBookCommand extends BaseCommand{

	public static function create(Plugin $plugin) : self{
		return new self($plugin, "evalbook", "EvalBook commands");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$this->sendUsage();
	}

	protected function prepare() : void{
		$this->setPermission(EvalBookPermissionNames::COMMAND);

		$subCommands = [
			new ReloadCommand($this->plugin, "reload", "Reload permitted operators configuration file"),
			new ChangeBookPermissionCommand($this->plugin, "perm", "Set the book's execution permission", ["permission"]),
			new ChangeBookNameCommand($this->plugin, "name", "Rename the book", ["customname"]),
			new GetBookCommand($this->plugin, "get", "Get a new eval book", ["new"]),
			new GiveCommand($this->plugin, "give", "Give an empty eval book to a player"),
		];
		foreach($subCommands as $subCommand){
			$this->registerSubCommand($subCommand);
		}
	}
}
