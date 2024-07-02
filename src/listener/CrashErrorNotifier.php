<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\listener;

use NeiroNetwork\EvalBook\ServerCrashTracer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

final class CrashErrorNotifier implements Listener{

	private array $sentPlayers = [];

	public function __construct(
		private readonly TaskScheduler $scheduler,
	){}

	/** @noinspection PhpUnused */
	public function onJoin(PlayerJoinEvent $event) : void{
		$player = $event->getPlayer();
		$error = ServerCrashTracer::getInstance()->getErrorMessage();

		if(isset($this->sentPlayers[$player->getName()]) || is_null($error)){
			return;
		}

		$this->scheduler->scheduleDelayedTask(new ClosureTask(function() use ($player, $error){
			if(!$player->isOnline()){
				return;
			}
			$player->sendMessage("EvalBook によるクラッシュを検出しました:\n§c$error");
			$this->sentPlayers[$player->getName()] = true;
		}), 20);
	}
}
