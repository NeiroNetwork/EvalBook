<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\WritableBook;
use pocketmine\item\WritableBookBase;
use pocketmine\item\WritableBookPage;
use pocketmine\item\WrittenBook;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

final class ExecutableBook{

	public static function validItem(Item $item) : bool{
		try{ self::getPermission($item); }catch(\AssertionError){ return false; }
		if(!$item instanceof WritableBookBase) return false;
		return $item->getNamedTag()->getByte("EvalBook", 0) === 1;
	}

	public static function getPermission(Item $item) : Permission{
		$name = "evalbook.execute.{$item->getLore()[0]}";
		$permission = PermissionManager::getInstance()->getPermission($name);
		assert($permission instanceof Permission);
		return $permission;
	}

	public static function getCode(WritableBookBase $book) : string{
		$pages = array_map(fn(WritableBookPage $page) : string => $page->getText(), $book->getPages());
		$pages = array_filter($pages, fn(string $text) : bool => trim($text) !== "");
		return implode(PHP_EOL, $pages);
	}

	// FIXME: 関数のやりたいことが明確じゃない(ちゃんと設計されてない)
	public static function makeWritable(WritableBookBase $base = null) : WritableBook{
		$book = VanillaItems::WRITABLE_BOOK()
			->setPages($base?->getPages() ?? [])
			->setCustomName(empty($base?->getCustomName()) ? "EvalBook" : $base->getCustomName())
			->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 10))
			->setLore(empty($base?->getLore()[0]) ? ["default"] : $base->getLore());

		$book->getNamedTag()->setByte("EvalBook", 1);

		return $book;
	}

	// FIXME: 関数の名前が妥当じゃない気がする
	public static function makeWritten(WrittenBook $book) : WrittenBook{
		$book->setCustomName($book->getTitle());
		$book->getNamedTag()->setByte("EvalBook", 1);
		return $book;
	}
}
