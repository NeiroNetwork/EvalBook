<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\listener;

use NeiroNetwork\EvalBook\LibEvalBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerEditBookEvent;
use pocketmine\item\WritableBook;
use pocketmine\item\WritableBookBase;
use pocketmine\item\WrittenBook;
use pocketmine\network\mcpe\protocol\BookEditPacket;

final readonly class BookEventListener implements Listener{

	/**
	 * 本の文字数制限を変更するリソースパックなどを使っている場合に、本が正常に保存されるようにします
	 *
	 * @handleCancelled
	 * @priority LOWEST
	 *
	 * @noinspection PhpUnused
	 */
	public function allowInvalidBook(PlayerEditBookEvent $event) : void{
		if($event->isCancelled() && $event->getPlayer()->hasPermission(EvalBookPermissionNames::BYPASS_BOOK_SOFT_LIMIT)){
			$event->uncancel();
		}
	}

	/** @noinspection PhpUnused */
	public function handleBookSigning(PlayerEditBookEvent $event) : void{
		if($event->getAction() === BookEditPacket::TYPE_SIGN_BOOK && LibEvalBook::isEvalBook($event->getOldBook())){
			$oldBook = $event->getOldBook();
			$newBook = $event->getNewBook();
			assert($oldBook instanceof WritableBook);
			assert($newBook instanceof WrittenBook);

			LibEvalBook::signEvalBook($oldBook, $newBook, $event->getPlayer());
		}
	}

	/** @noinspection PhpUnused */
	public function executeDroppedEvalBook(PlayerDropItemEvent $event) : void{
		$player = $event->getPlayer();
		$item = $event->getItem();
		if($player->isSneaking() && LibEvalBook::isEvalBook($item)){
			assert($item instanceof WritableBookBase);
			if(LibEvalBook::hasPermission($item, $player)){
				$event->cancel();
				LibEvalBook::executeBook($item, $player);
			}
		}
	}
}
