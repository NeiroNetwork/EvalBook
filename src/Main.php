<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use CortexPE\Commando\PacketHooker;
use NeiroNetwork\EvalBook\command\EvalBookCommand;
use NeiroNetwork\EvalBook\crashtracer\CrashTracer;
use NeiroNetwork\EvalBook\listener\CrashErrorNotifier;
use NeiroNetwork\EvalBook\listener\PermissionGranter;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
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
		CrashTracer::readLastError($this);
	}

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new CrashErrorNotifier($this->getScheduler()), $this);
		$this->getServer()->getPluginManager()->registerEvents(new PermissionGranter(), $this);
		OperatorsStore::load($this->getDataFolder());
		$this->getServer()->getCommandMap()->register($this->getName(), new EvalBookCommand($this, "evalbook", "EvalBook commands"));
		if(!PacketHooker::isRegistered()) PacketHooker::register($this);
	}

	protected function onDisable() : void{
		CrashTracer::catchBadError($this);
	}
}
