<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\crashtracer;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\ClosureTask;

class CrashErrorNotifier implements Listener{

	private array $sentPlayers = [];

	public function __construct(private Plugin $plugin){}

	public function onJoin(PlayerJoinEvent $event) : void{
		$player = $event->getPlayer();
		$name = $player->getName();

		if(!CrashTracer::hasError() || isset($this->sentPlayers[$name])) return;

		$this->sentPlayers[$name] = true;

		$this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(
			fn() => $player->sendMessage(CrashTracer::getErrorMessage())
		), 20);
	}
}
