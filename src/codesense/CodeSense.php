<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use pocketmine\player\Player;

final class CodeSense{

	public static function doAll(string $code, mixed $executor) : string{
		new VarDumpForPlayer();
		self::injectImports($code);
		if($executor instanceof Player) self::injectBookExecutedPlayer($code, $executor);
		return $code;
	}

	private static function injectImports(string &$code) : void{
		$importString = "";
		foreach(Imports::get() as $class){
			if(!str_contains($code, $class)){
				$importString .= "use $class;";
			}
		}

		$code = $importString . $code;
	}

	private static function injectBookExecutedPlayer(string &$code, Player $player) : void{
		$list = [];
		foreach(["player", "executor", "executer"] as $str){
			$STR = strtoupper($str);
			array_push($list, "\$_$str", "\$_{$str}_", "\$_$STR", "\$_{$STR}_");
		}

		$getPlayer = "\pocketmine\Server::getInstance()->getPlayerExact('{$player->getName()}')";
		$code = implode("=", $list) . "=$getPlayer;" . $code;
	}
}
