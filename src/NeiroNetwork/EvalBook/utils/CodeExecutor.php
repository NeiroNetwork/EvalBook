<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use pocketmine\utils\SingletonTrait;

class CodeExecutor extends FakePluginBase{
	use SingletonTrait;

	public static function eval(string $code) : void{
		self::getInstance()->evalInternal($code);
	}

	private function evalInternal(string $code) : void{
		eval($code);
	}
}