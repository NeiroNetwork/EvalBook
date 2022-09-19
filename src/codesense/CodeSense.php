<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use pocketmine\player\Player;

class CodeSense{

	public static function injectImport(string $code) : string{
		$importString = "";
		foreach(Imports::get() as $class){
			if(!str_contains($code, $class)){
				$importString .= "use $class;";
			}
		}
		return $importString . $code;
	}

	public static function injectBookExecutedPlayer(string $code, Player $sender) : string{
		$list = [];
		foreach(["player", "executor", "executer"] as $str){
			$STR = strtoupper($str);
			array_push($list, "\$_$str", "\$_{$str}_", "\$_$STR", "\$_{$STR}_");
		}
		return implode("=", $list) . " = \pocketmine\Server::getInstance()->getPlayerExact('{$sender->getName()}');$code";
	}
}
