<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use NeiroNetwork\EvalBook\codesense\CodeSense;
use NeiroNetwork\EvalBook\Main;
use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use NeiroNetwork\EvalBook\utils\ExceptionUtils;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\WritableBookBase;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\utils\TextFormat;

abstract class ExecutableBook{

	public static function isExcutableBook(Item $item) : bool{
		return $item->getNamedTag()->getByte("EvalBook", 0) === 1;
	}

	public static function getPermission(WritableBookBase|string $bookOrPerm) : ?Permission{
		if(!is_string($bookOrPerm)){
			$bookOrPerm = $bookOrPerm->getLore()[0] ?? "";
		}
		return PermissionManager::getInstance()->getPermission(EvalBookPermissionNames::EVALBOOK_EXECUTE . ".$bookOrPerm");
	}

	public static function execute(WritableBookBase $book, CommandSender $executor = null) : bool{
		var_dump($code = self::parseBookCode($book));
		$sense = CodeSense::injectImport($code);
		if($executor !== null){
			$sense = CodeSense::injectBookExecutedPlayer($sense, $executor);
		}
		try{
			Main::getInstance()->eval($sense);
		}catch(\Throwable $exception){
			/**
			 * fatal error はどうあがいてもキャッチできない
			 * 例えば: クラスの間違った継承、クラスや関数を2回以上定義する
			 */
			$executor?->sendMessage(TextFormat::RED . ExceptionUtils::toString($exception));
			return false;
		}
		return true;
	}

	public static function parseBookCode(WritableBookBase $book) : string{
		$stack = [];
		foreach($book->getPages() as $page){
			if(!empty($text = trim($page->getText()))){
				$stack[] = $text;
			}
		}
		return implode(PHP_EOL, $stack);
	}
}