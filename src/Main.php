<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use CortexPE\Commando\PacketHooker;
use NeiroNetwork\EvalBook\command\EvalBookCommand;
use NeiroNetwork\EvalBook\item\EvalBookEnchantment;
use NeiroNetwork\EvalBook\listener\BookEventListener;
use NeiroNetwork\EvalBook\listener\CrashErrorNotifier;
use NeiroNetwork\EvalBook\listener\PermissionGranter;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\plugin\PluginBase;

final class Main extends PluginBase{

	private static Main $instance;

	public static function getPlugin() : Main{
		return self::$instance;
	}

	protected function onLoad() : void{
		self::$instance = $this;
		ServerCrashTracer::disableAutoReporting();
		EvalBookPermissions::registerCorePermissions();
	}

	protected function onEnable() : void{
		if(!PacketHooker::isRegistered()) PacketHooker::register($this);

		$this->getServer()->getCommandMap()->register($this->getName(), EvalBookCommand::create($this));

		EvalBookEnchantment::getInstance();	// We must register the enchantment first

		ServerCrashTracer::getInstance()->readLastError($this->getDataFolder());
		$this->getServer()->getPluginManager()->registerEvents(new CrashErrorNotifier($this->getScheduler()), $this);
		$this->getServer()->getPluginManager()->registerEvents(new PermissionGranter(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new BookEventListener(), $this);
	}

	protected function onDisable() : void{
		ServerCrashTracer::getInstance()->catchBadError($this->getDataFolder());
	}
}
