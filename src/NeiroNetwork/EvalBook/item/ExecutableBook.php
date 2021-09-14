<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;
use NeiroNetwork\EvalBook\utils\CodeExecutor;
use NeiroNetwork\EvalBook\utils\Exception;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\WritableBookBase;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\utils\TextFormat;

abstract class ExecutableBook{

	public static function equals(Item $item) : bool{
		return (EvalBook::equalsInternal($item) || CodeBook::equalsInternal($item)) && ExecutableBook::equalsInternal($item);
	}

	protected static function equalsInternal(Item $item) : bool{
		return isset(($lore = $item->getLore())[0])
			&& ($lore[0] === "default" || $lore[0] === "op" || $lore[0] === "everyone");
	}

	public static function getExecutePermission(WritableBookBase|string $bookOrPerm) : ?Permission{
		if(!is_string($bookOrPerm)){
			$bookOrPerm = $bookOrPerm->getLore()[0] ?? "";
		}
		return PermissionManager::getInstance()->getPermission(EvalBookPermissionNames::EVALBOOK_EXECUTE . ".$bookOrPerm");
	}

	public static function execute(WritableBookBase $book, CommandSender $executor = null) : bool{
		try{
			var_dump($code = self::parseBookCode($book));
			CodeExecutor::eval($code);
		}catch(\Throwable $exception){
			// fatal error はどうあがいてもキャッチできない
			// 例えばクラスの間違った継承やクラスの重複した登録
			$executor?->sendMessage(TextFormat::RED . Exception::toString($exception));
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