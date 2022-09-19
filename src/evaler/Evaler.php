<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\evaler;

use NeiroNetwork\EvalBook\codesense\CodeSense;
use NeiroNetwork\EvalBook\Main;
use pocketmine\command\CommandSender;
use pocketmine\errorhandler\ErrorTypeToStringMap;
use pocketmine\player\Player;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;

final class Evaler{

	public static function promote(string $code, CommandSender $executor = null) : void{
		var_dump($code);

		$sense = CodeSense::injectImport($code);
		if($executor instanceof Player){
			$sense = CodeSense::injectBookExecutedPlayer($sense, $executor);
		}

		try{
			Main::evalPhp($sense);
		}catch(\Throwable $exception){
			/**
			 * fatal error はどうあがいてもキャッチできない
			 * 例えば: クラスの間違った継承、クラスや関数を2回以上定義する
			 */
			$executor?->sendMessage(TextFormat::RED . self::exceptionToString($exception));
		}
	}

	/**
	 * @see MainLogger::printExceptionMessage()
	 */
	private static function exceptionToString(\Throwable $e) : string{
		$errorString = preg_replace('/\s+/', ' ', trim($e->getMessage()));
		if(is_int($errorNumber = $e->getCode())){
			try{
				$errorNumber = ErrorTypeToStringMap::get($errorNumber);
			}catch(\InvalidArgumentException){}
		}
		return get_class($e) . ": \"$errorString\" ($errorNumber) at line {$e->getLine()}";
	}
}
