<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use CortexPE\Commando\PacketHooker;
use NeiroNetwork\EvalBook\codesense\Imports;
use NeiroNetwork\EvalBook\command\EvalBookCommand;
use NeiroNetwork\EvalBook\crashtracer\CrashErrorNotifier;
use NeiroNetwork\EvalBook\crashtracer\CrashTracer;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use NeiroNetwork\EvalBook\permission\PermissionGranter;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	private static self $instance;

	public static function getInstance() : self{
		return self::$instance;
	}

	/**
	 * @internal
	 */
	public function eval(string $code) : void{
		eval($code);
	}

	protected function onLoad() : void{
		self::$instance = $this;

		EvalBookPermissions::registerPermissions();
		Imports::getInstance();
		CrashTracer::readLastError($this);
	}

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new CrashErrorNotifier($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new PermissionGranter(), $this);
		OperatorsStore::load($this->getDataFolder());
		$this->getServer()->getCommandMap()->register($this->getName(), new EvalBookCommand($this, "evalbook"));
		if(!PacketHooker::isRegistered()) PacketHooker::register($this);
	}

	protected function onDisable() : void{
		CrashTracer::catchBadError($this);
	}
}
