<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\listener;

use NeiroNetwork\EvalBook\evaluator\Evaluator;
use NeiroNetwork\EvalBook\item\ExecutableBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerEditBookEvent;
use pocketmine\item\WritableBookBase;
use pocketmine\item\WrittenBook;
use pocketmine\network\mcpe\protocol\BookEditPacket;

class BookEventListener implements Listener{

	/**
	 * 本の文字数制限を変更するリソースパックなどを使っている場合に、本が正常に保存されるようにします
	 *
	 * @handleCancelled
	 * @priority LOWEST
	 */
	public function allowInvalidBook(PlayerEditBookEvent $event) : void{
		if($event->isCancelled() && $event->getPlayer()->hasPermission(EvalBookPermissions::ROOT_OPERATOR)){
			$event->uncancel();
		}
	}

	public function onEditBook(PlayerEditBookEvent $event) : void{
		if($event->getAction() !== BookEditPacket::TYPE_SIGN_BOOK) return;

		$oldBook = $event->getOldBook();
		if(!ExecutableBook::validItem($oldBook)) return;

		$newBook = $event->getNewBook();
		assert($newBook instanceof WrittenBook);

		$newBook->setAuthor($event->getPlayer()->getName())->setLore($oldBook->getLore());
		ExecutableBook::makeWritten($newBook);

		// $newBook を直接変更しているので、set関数を呼び出す必要は無いが、一応…
		$event->setNewBook($newBook);
	}

	public function onDropItem(PlayerDropItemEvent $event) : void{
		if(
			($player = $event->getPlayer())->isSneaking() &&
			ExecutableBook::validItem($item = $event->getItem()) &&
			$player->hasPermission(ExecutableBook::getPermission($item))
		){
			$event->cancel();
			/** @var WritableBookBase $item */
			Evaler::promote(ExecutableBook::getCode($item), $player);
		}
	}
}
