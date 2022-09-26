<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\listener;

use NeiroNetwork\EvalBook\crashtracer\CrashTracer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class CrashErrorNotifier implements Listener{

	private array $sentPlayers = [];

	public function __construct(private TaskScheduler $scheduler){}

	public function onJoin(PlayerJoinEvent $event) : void{
		$player = $event->getPlayer();
		$name = $player->getName();

		if(!CrashTracer::hasError() || isset($this->sentPlayers[$name])) return;

		$this->sentPlayers[$name] = true;

		$this->scheduler->scheduleDelayedTask(new ClosureTask(
			fn() => $player->sendMessage("EvalBookによるクラッシュを検出しました:\n§c" . CrashTracer::getErrorMessage())
		), 20);
	}
}
