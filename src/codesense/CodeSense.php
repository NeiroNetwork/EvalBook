<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use Generator;
use ParseError;
use PhpToken;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final readonly class CodeSense{

	public static function preprocess(string $code, ?CommandSender $executor = null) : string{
		new VarDumpForPlayer();    // Load var_dump_p() function

		$result = "";
		foreach(self::splitByNamespace($code) as $splitCodes){
			if(count($splitCodes) <= 1){
				array_unshift($splitCodes, "");
			}

			$injection = self::generateImports($splitCodes[0] . $splitCodes[1]);
			if($executor instanceof Player){
				$injection .= self::generatePlayerVariables($executor);
			}

			$result .= $splitCodes[0] . $injection . $splitCodes[1];
		}
		return $result;
	}

	/**
	 * @return Generator<string[]>
	 */
	private static function splitByNamespace(string $code) : Generator{
		$tokens = PhpToken::tokenize("<?php $code");
		next($tokens);  // Skip the opening tag

		/** @var string[] $splitCode */
		$splitCode = [];
		$temp = "";
		while($token = current($tokens)){
			if($token->is(T_NAMESPACE)){
				if($temp !== ""){
					$splitCode[] = $temp;
					$temp = "";
				}
				if(!empty($splitCode)){
					yield $splitCode;
					$splitCode = [];
				}
				while($token = current($tokens)){
					$temp .= $token->text;
					if($token->is([';', '{'])){
						$splitCode[] = $temp;
						$temp = "";
						break;
					}
					next($tokens);
				}
			}else{
				$temp .= $token->text;
			}
			next($tokens);
		}
		if($temp !== ""){
			$splitCode[] = $temp;
		}
		if(!empty($splitCode)){
			yield $splitCode;
		}
	}

	private static function generateImports(string $code) : string{
		$baseImports = ImportablePmClasses::getInstance()->getImportableClasses();
		try{
			$userImports = UseStatementParser::parse("<?php $code");
		}catch(ParseError){
			return "";
		}

		foreach($userImports as $name => $_){
			unset($baseImports[$name]);
		}

		return implode("", array_map(fn(string $class) => "use $class;", $baseImports));
	}

	private static function generatePlayerVariables(Player $player) : string{
		$variables = [];
		foreach(["player", "executor", "executer"] as $str){
			$STR = strtoupper($str);
			array_push($variables, "\$_$str", "\$_{$str}_", "\$_$STR", "\$_{$STR}_");
		}

		return implode("=", $variables) . "=\pocketmine\Server::getInstance()->getPlayerExact('{$player->getName()}');";
	}
}
