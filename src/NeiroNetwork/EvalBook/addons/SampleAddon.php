<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\addons;

use NeiroNetwork\EvalBook\codesense\Addon;

class SampleAddon extends Addon {

	public static function sample(): string{
		return "Hello, World!";
	}

	public function onInject(string &$code): void{
		
	}
}