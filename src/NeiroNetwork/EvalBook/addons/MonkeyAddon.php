<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\addons;

use NeiroNetwork\EvalBook\codesense\Addon;
use pocketmine\command\CommandSender;

class MonkeyAddon extends Addon {

	public static function math(): string{
		return "1 + 1 = 2";
	}

	public static function random(): int{
		return mt_rand(0, 10);
	}

	public function __construct(){
		$this->setName("Monkey");
	}

	public function onInject(string &$code, ?CommandSender $executor = null): void{
		if ($executor !== null){
			$code .= '$_player->sendMessage("Monkey!");';
		}
	}
}