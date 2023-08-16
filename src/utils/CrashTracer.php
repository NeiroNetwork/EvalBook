<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use NeiroNetwork\EvalBook\Main;
use pocketmine\thread\ThreadCrashInfoFrame;
use Symfony\Component\Filesystem\Path;

final class CrashTracer{

	private static string $error = "";

	public static function hasError() : bool{ return self::$error !== ""; }

	public static function getErrorMessage() : string{ return self::$error; }

	public static function readLastError(Main $plugin) : void{
		$path = Path::join($plugin->getDataFolder(), "last_error.txt");
		if(file_exists($path)){
			self::$error = file_get_contents($path);
			unlink($path);
		}
	}

	public static function catchBadError(Main $plugin) : void{
		global $lastExceptionError;
		if(empty($error = $lastExceptionError)) return;	// fatal error

		if(self::causedByPlugin($error)){
			$path = Path::join($plugin->getDataFolder(), "last_error.txt");
			$message = "{$error["type"]}: \"{$error["message"]}\" in \"{$error["file"]}\" at line {$error["line"]}";
			file_put_contents($path, $message);
		}
	}

	/**
	 * @param array{
	 *     type: string,
	 *     message: string,
	 *     fullFile: string,
	 *     file: string,
	 *     line: int,
	 *     trace: ThreadCrashInfoFrame[]
	 * } $error
	 *
	 * @return bool
	 */
	private static function causedByPlugin(array $error) : bool{
		if(self::isEvaldFile($error["fullFile"])) return true;

		foreach($error["trace"] as $trace){
			if(self::isEvaldFile($trace->getFile() ?? "")){
				return true;
			}
		}

		return false;
	}

	private static function isEvaldFile(string $file) : bool{
		return str_contains($file, "EvalBook") && str_contains($file, "eval()'d code");
	}
}
