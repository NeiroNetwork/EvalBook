<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use pocketmine\errorhandler\ErrorTypeToStringMap;
use pocketmine\utils\MainLogger;

final class ExceptionUtils{

	/**
	 * @see MainLogger::printExceptionMessage()
	 */
	public static function toString(\Throwable $e) : string{
		$errstr = preg_replace('/\s+/', ' ', trim($e->getMessage()));
		if(is_int($errno = $e->getCode())){
			try{
				$errno = ErrorTypeToStringMap::get($errno);
			}catch(\InvalidArgumentException){
			}
		}
		return get_class($e) . ": \"$errstr\" ($errno) at line {$e->getLine()}";
	}
}