<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\WritableBook;
use pocketmine\item\WrittenBook;
use pocketmine\player\Player;

final class CodeBook extends ExecutableBook{

	public static function equals(Item $item) : bool{
		return self::equalsInternal($item) && parent::equalsInternal($item);
	}

	protected static function equalsInternal(Item $item) : bool{
		return VanillaItems::WRITTEN_BOOK()->equals($item, true, false) && $item->hasCustomName();
	}

	public static function create(WritableBook $before, WrittenBook $after, Player $author = null) : WrittenBook{
		if($author !== null){
			$after->setAuthor($author->getName());
		}
		return $after->setCustomName($after->getTitle())->setLore($before->getLore());
	}
}