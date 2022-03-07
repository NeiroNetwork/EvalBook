<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\addons\HelloWorldAddon;
use NeiroNetwork\EvalBook\addons\SampleAddon;
use NeiroNetwork\EvalBook\codesense\Addon;
use NeiroNetwork\EvalBook\codesense\Imports;
use NeiroNetwork\EvalBook\command\EvalBookCommand;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use NeiroNetwork\EvalBook\utils\CrashTracer;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Webmozart\PathUtil\Path;

class Main extends PluginBase{

	private static self $instance;
	private Config $operators;

	public static function getInstance() : self{
		return self::$instance;
	}

	public function eval(string $code) : void{
		eval($code);
	}

	public function getOperators() : Config{
		return $this->operators;
	}

	protected function onLoad() : void{
		Imports::getInstance();
	}

	protected function onEnable() : void{
		self::$instance = $this;
		EvalBookPermissions::registerPermissions();
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		$this->operators = new Config(Path::join($this->getDataFolder(), "allowlist.txt"), Config::ENUM);
		$this->getServer()->getCommandMap()->register($this->getName(), new EvalBookCommand("evalbook"));

		Addon::registerAddon(new HelloWorldAddon);

		CrashTracer::tryReadLastError();
	}

	protected function onDisable() : void{
		CrashTracer::catchLastError();
	}
}