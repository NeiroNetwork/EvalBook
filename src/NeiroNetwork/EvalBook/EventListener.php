<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\item\WrittenExecutableBook;
use NeiroNetwork\EvalBook\item\ExecutableBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use NeiroNetwork\EvalBook\utils\CrashTracer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerEditBookEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\item\WritableBook;
use pocketmine\item\WritableBookBase;
use pocketmine\item\WrittenBook;
use pocketmine\network\mcpe\protocol\BookEditPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;

class EventListener implements Listener{

	public function onLogin(PlayerLoginEvent $event) : void{
		$player = $event->getPlayer();
		if(Main::getInstance()->getOperators()->exists($player->getName(), true)){
			$player->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
		}
	}

	public function onEditBook(PlayerEditBookEvent $event) : void{
		if($event->getAction() === BookEditPacket::TYPE_SIGN_BOOK && ExecutableBook::isExecutableBook($event->getOldBook())){
			$oldBook = $event->getOldBook();
			assert($oldBook instanceof WritableBook);
			$newBook = $event->getNewBook();
			assert($newBook instanceof WrittenBook);
			$event->setNewBook(WrittenExecutableBook::create($oldBook, $newBook, $event->getPlayer()));
		}
	}

	public function onDropItem(PlayerDropItemEvent $event) : void{
		if(($player = $event->getPlayer())->isSneaking()
			&& ExecutableBook::isExecutableBook($item = $event->getItem())
			&& $player->hasPermission(ExecutableBook::getPermission($item))
		){
			$event->cancel();
			/** @var WritableBookBase $item */
			ExecutableBook::execute($item, $player);
		}
	}

	public function onJoin(PlayerJoinEvent $event) : void{
		Main::getInstance()->getScheduler()->scheduleDelayedTask(new class($event->getPlayer()) extends Task{
			public function __construct(private Player $player){}

			public function onRun() : void{
				if($this->player->isOnline()){
					CrashTracer::notifyTo($this->player);
				}
			}
		}, 20);
	}
}