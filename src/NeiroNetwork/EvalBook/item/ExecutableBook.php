<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\Item;

abstract class ExecutableBook{

	public static function equals(Item $item) : bool{
		return (EvalBook::equalsInternal($item) || CodeBook::equalsInternal($item)) && ExecutableBook::equalsInternal($item);
	}

	protected static function equalsInternal(Item $item) : bool{
		return isset(($lore = $item->getLore())[0])
			&& ($lore[0] === "default" || $lore[0] === "op" || $lore[0] === "everyone");
	}
}