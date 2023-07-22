<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use CortexPE\Commando\PacketHooker;
use NeiroNetwork\EvalBook\command\EvalBookCommand;
use NeiroNetwork\EvalBook\utils\CrashReportDisabler;
use NeiroNetwork\EvalBook\utils\CrashTracer;
use NeiroNetwork\EvalBook\listener\BookEventListener;
use NeiroNetwork\EvalBook\listener\CrashErrorNotifier;
use NeiroNetwork\EvalBook\listener\PermissionGranter;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\plugin\PluginBase;

#[\AllowDynamicProperties]
class Main extends PluginBase{

	private static self $instance;

	public static function evalPhp(string $code) : void{ self::$instance->evalInternal($code); }

	private function evalInternal(string $code) : void{ eval($code); }

	protected function onLoad() : void{
		self::$instance = $this;

		EvalBookPermissions::registerPermissions();

		CrashReportDisabler::disableAutoReport($this->getServer());
		CrashTracer::readLastError($this);
	}

	protected function onEnable() : void{
		OperatorsStore::load($this->getDataFolder());

		if(!PacketHooker::isRegistered()) PacketHooker::register($this);
		$this->getServer()->getCommandMap()->register($this->getName(), new EvalBookCommand($this, "evalbook", "EvalBook commands"));

		$this->getServer()->getPluginManager()->registerEvents(new CrashErrorNotifier($this->getScheduler()), $this);
		$this->getServer()->getPluginManager()->registerEvents(new PermissionGranter(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new BookEventListener(), $this);
	}

	protected function onDisable() : void{
		CrashTracer::catchBadError($this);
	}
}
