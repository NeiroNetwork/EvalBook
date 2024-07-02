<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\listener;

use NeiroNetwork\EvalBook\EvalBookOperators;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

final readonly class PermissionGranter implements Listener{

	/** @noinspection PhpUnused */
	public function onLogin(PlayerLoginEvent $event) : void{
		$player = $event->getPlayer();
		if(EvalBookOperators::getInstance()->exists($player->getName())){
			$player->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
		}
	}
}
