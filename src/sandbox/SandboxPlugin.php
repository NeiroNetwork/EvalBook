<?php

//declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox;

use AllowDynamicProperties;
use NeiroNetwork\EvalBook\sandbox\fakeplugin\FakePluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\SingletonTrait;

#[AllowDynamicProperties]
final class SandboxPlugin extends FakePluginBase implements Listener{
	use SingletonTrait;

	private static function make() : self{
		$plugin = self::create("EvalBookSandbox", "0.0.1");
		$plugin->getServer()->getPluginManager()->enablePlugin($plugin);
		return $plugin;
	}

	public function eval(string $code) : void{
		eval($code);
	}
}
