<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\WritableBook;
use pocketmine\item\WrittenBook;

final class CodeBook extends ExecutableBook{

	public static function equalsInternal(Item $item) : bool{
		return VanillaItems::WRITTEN_BOOK()->equals($item, true, false)
			&& $item->getCustomName() === "CodeBook";
	}

	public static function create(WritableBook $before, WrittenBook $after) : WrittenBook{
		return $after->setCustomName("CodeBook")->setLore($before->getLore());
	}
}