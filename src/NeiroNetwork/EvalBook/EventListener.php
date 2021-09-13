<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener{

	public function onLogin(PlayerLoginEvent $event) : void{
		$player = $event->getPlayer();
		if(Main::getInstance()->getOperators()->exists($player->getName(), true)){
			$player->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
		}
	}
}