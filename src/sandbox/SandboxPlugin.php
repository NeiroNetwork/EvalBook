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

	public function eval(string $code, bool $flushOutput = true) : string{
		if($flushOutput){
			$output = "";
			ob_start(function(string $buffer) use (&$output){
				$output .= $buffer;
				return $buffer;
			}, 1);
		}else{
			ob_start();
		}

		eval($code);

		if($flushOutput){
			ob_end_flush();
		}else{
			$output = ob_get_clean();
		}

		return $output;
	}
}
