<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use ParseError;
use pocketmine\player\Player;

final readonly class CodeSense{

	public static function preprocess(string $code, mixed $executor) : string{
		new VarDumpForPlayer();    // Load var_dump_p() function
		self::injectImports($code);
		if($executor instanceof Player){
			self::injectBookExecutedPlayer($code, $executor);
		}
		return $code;
	}

	private static function injectImports(string &$code) : void{
		$baseImports = ImportablePmClasses::getInstance()->getImportableClasses();
		try{
			$userImports = UseStatementParser::parse("<?php $code");
		}catch(ParseError){
			return;
		}

		foreach($userImports as $name => $_){
			unset($baseImports[$name]);
		}

		$importString = implode("", array_map(fn(string $class) => "use $class;", $baseImports));
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
