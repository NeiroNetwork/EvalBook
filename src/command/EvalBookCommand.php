<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;

class EvalBookCommand extends BaseCommand{

	protected function prepare() : void{
		$this->registerSubCommand(new ReloadCommand("reload"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$this->sendUsage();
	}
}
