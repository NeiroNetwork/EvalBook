<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use pocketmine\Server;
use pocketmine\thread\ThreadCrashInfoFrame;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

final class ServerCrashTracer{
	use SingletonTrait;

	public static function disableAutoReporting() : void{
		$config = Server::getInstance()->getConfigGroup();
		$enabled = $config->getConfigBool("auto-report.enabled");
		$host = $config->getConfigString("auto-report.host");
		if($enabled && str_ends_with($host, ".pmmp.io")){
			$propertyCache = (new \ReflectionClass($config))->getProperty("propertyCache");
			$propertyCache->setValue($config, ["auto-report.enabled" => false]);
		}
	}

	private ?string $error = null;

	public function __construct(){}

	public function getErrorMessage() : ?string{
		return $this->error;
	}

	public function readLastError(string $pluginDataFolder) : void{
		$path = Path::join($pluginDataFolder, "last_error.txt");
		if(file_exists($path)){
			$this->error = file_get_contents($path);
			unlink($path);
		}
	}

	public function catchBadError(string $pluginDataFolder) : void{
		global $lastExceptionError;
		if(empty($error = $lastExceptionError)){    // fatal error
			return;
		}

		if($this->causedByPlugin($error)){
			$path = Path::join($pluginDataFolder, "last_error.txt");
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
	 *     trace: ThreadCrashInfoFrame[],
	 *     thread: string
	 * } $error
	 */
	private function causedByPlugin(array $error) : bool{
		$isEvaldFile = fn(string $file) => str_contains($file, "EvalBook") && str_contains($file, "eval()'d code");

		if($isEvaldFile($error["fullFile"])){
			return true;
		}

		foreach($error["trace"] as $trace){
			if(!is_null($trace->getFile()) && $isEvaldFile($trace->getFile())){
				return true;
			}
		}

		return false;
	}
}
