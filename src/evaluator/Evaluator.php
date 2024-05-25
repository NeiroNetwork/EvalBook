<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\evaluator;

use NeiroNetwork\EvalBook\codesense\CodeSense;
use NeiroNetwork\EvalBook\Main;
use pocketmine\command\CommandSender;
use pocketmine\errorhandler\ErrorTypeToStringMap;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;

final class Evaluator{

	public static function promote(string $code, CommandSender $executor = null) : void{
		var_dump($code);	// TODO: log ran scripts

		$code = CodeSense::preprocess($code, $executor);

		try{
			Main::evalPhp($code);
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
