<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use pocketmine\command\CommandSender;

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

	public static function injectAddon(string $code, Addon $addon) : string{
		$use = "use " . $addon::class;
		$code = $use . $code;
		$addon->onInject($code);
		return $code;
	}

	public static function injectBookExecutedPlayer(string $code, CommandSender $sender) : string{
		$list = [];
		foreach(["player", "executor", "executer"] as $str){
			$STR = strtoupper($str);
			array_push($list, "\$_$str", "\$_{$str}_", "\$_$STR", "\$_{$STR}_");
		}
		return implode("=", $list) . " = \pocketmine\Server::getInstance()->getPlayerExact('{$sender->getName()}');$code";
	}
}