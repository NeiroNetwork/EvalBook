<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\addons;

use NeiroNetwork\EvalBook\codesense\Addon;
use pocketmine\command\CommandSender;

class HelloWorldAddon extends Addon {

	public static function sample(): string{
		return "Hello, World!";
	}

	public function __construct(){
		$this->setName("HelloWorld");
	}

	public function onInject(string &$code, ?CommandSender $executor = null): void{
		if ($executor !== null){
			$code .= '$_player->sendMessage("Hello, World!");';
		}
	}
}