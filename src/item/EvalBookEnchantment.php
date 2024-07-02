<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\Rarity;
use pocketmine\utils\SingletonTrait;

final class EvalBookEnchantment extends Enchantment{
	use SingletonTrait;

	private const ENCHANTMENT_ID = 16367;

	public function __construct(){
		parent::__construct("EvalBook", Rarity::COMMON, 0, 0, 0);
		EnchantmentIdMap::getInstance()->register(self::ENCHANTMENT_ID, $this);
	}
}
