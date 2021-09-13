<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\item\CodeBook;
use NeiroNetwork\EvalBook\item\EvalBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEditBookEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\network\mcpe\protocol\BookEditPacket;

class EventListener implements Listener{

	public function onLogin(PlayerLoginEvent $event) : void{
		$player = $event->getPlayer();
		if(Main::getInstance()->getOperators()->exists($player->getName(), true)){
			$player->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
		}
	}

	public function onEditBook(PlayerEditBookEvent $event) : void{
		if($event->getAction() === BookEditPacket::TYPE_SIGN_BOOK && EvalBook::equals($event->getOldBook())){
			$event->setNewBook(CodeBook::create($event->getOldBook(), $event->getNewBook()));
		}
	}
}