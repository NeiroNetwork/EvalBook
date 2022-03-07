<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use pocketmine\command\CommandSender;
use Reflection;
use ReflectionClass;

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

	public static function injectAddon(string $code, Addon $addon, ?CommandSender $executor = null) : string{
		$use = "use " . $addon::class;
		$shortName = (new ReflectionClass($addon))->getShortName();
		if ($shortName !== $addon->getName()){
			$use .= " as " . $addon->getName();
		}
		$code = $use . ";" . $code;
		$addon->onInject($code, $executor);
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