<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use NeiroNetwork\EvalBook\Main;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Webmozart\PathUtil\Path;

final class CrashTracer{

	private static ?string $lastError = null;
	private static array $notified = [];

	public static function getLastError() : ?string{
		return self::$lastError;
	}

	public static function hasLastError() : bool{
		return self::$lastError !== null;
	}

	public static function tryReadLastError() : void{
		$path = Path::join(Main::getInstance()->getDataFolder(), "lasterror.txt");
		if(file_exists($path)){
			self::$lastError = file_get_contents($path);
			unlink($path);
		}
	}

	public static function catchLastError() : void{
		global $lastExceptionError;
		if(empty($lastExceptionError)){
			// 恐らく fatal error が発生
			return;
		}

		$contains = fn(string $file) : bool => str_contains($file, "EvalBook") && str_contains($file, "eval()'d code");
		if(!($isPluginError = $contains($lastExceptionError["fullFile"]))){
			foreach($lastExceptionError["trace"] as $trace){
				if($isPluginError = $contains($trace["file"] ?? "")){
					break;
				}
			}
		}

		if($isPluginError){
			$error = "{$lastExceptionError["type"]}: \"{$lastExceptionError["message"]}\" in \"{$lastExceptionError["file"]}\" at line {$lastExceptionError["line"]}";
			$path = Path::join(Main::getInstance()->getDataFolder(), "lasterror.txt");
			file_put_contents($path, $error);
		}
	}

	public static function notifyTo(Player $player) : void{
		if($player->hasPermission(EvalBookPermissions::ROOT_OPERATOR) && self::hasLastError()){
			if(!isset(self::$notified[$player->getName()])){
				self::$notified[$player->getName()] = true;
				$player->sendMessage("EvalBookによるサーバークラッシュを検出しました:");
				$player->sendMessage(TextFormat::RED . self::getLastError());
			}
		}
	}
}