<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\WritableBook;

final class EvalBook extends ExecutableBook{

	public static function equals(Item $item) : bool{
		return self::equalsInternal($item) && parent::equalsInternal($item);
	}

	protected static function equalsInternal(Item $item) : bool{
		return VanillaItems::WRITABLE_BOOK()->equals($item, true, false)
			&& $item->getCustomName() === "EvalBook"
			&& $item->hasEnchantment(VanillaEnchantments::POWER());
	}

	public static function new() : WritableBook{
		return VanillaItems::WRITABLE_BOOK()
			->setCustomName("EvalBook")
			->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 10))
			->setLore(["default"]);
	}
}