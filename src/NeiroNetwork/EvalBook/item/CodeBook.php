<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\WritableBookBase;

final class CodeBook extends ExecutableBook{

	public static function equals(Item $item) : bool{
		return self::equalsInternal($item) && parent::equalsInternal($item);
	}

	protected static function equalsInternal(Item $item) : bool{
		return VanillaItems::WRITTEN_BOOK()->equals($item, true, false)
			&& $item->getCustomName() === "CodeBook";
	}

	public static function create(WritableBookBase $before, WritableBookBase $after) : WritableBookBase{
		return $after->setCustomName("CodeBook")->setLore($before->getLore());
	}
}