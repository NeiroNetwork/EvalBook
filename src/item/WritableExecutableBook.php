<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;
use pocketmine\item\WritableBook;

final class WritableExecutableBook extends ExecutableBook{

	public static function new() : WritableBook{
		$item = VanillaItems::WRITABLE_BOOK()
			->setCustomName("EvalBook")
			->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 10))
			->setLore(["default"]);
		$item->getNamedTag()->setByte("EvalBook", 1);
		return $item;
	}
}