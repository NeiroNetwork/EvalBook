<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use AttachableLogger;
use NeiroNetwork\EvalBook\Main;
use pocketmine\plugin\PluginLogger;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use Webmozart\PathUtil\Path;

class FakePluginBase{
	use SingletonTrait;

	private Server $server;
	private string $dataFolder;
	private PluginLogger $logger;
	private TaskScheduler $scheduler;

	public function __construct(){
		$this->server = Server::getInstance();
		$this->dataFolder = Path::join(Main::getInstance()->getDataFolder(), "data_folder");
		if(!file_exists($this->dataFolder)){
			mkdir($this->dataFolder, 0777, true);
		}
		$this->logger = new PluginLogger($this->server->getLogger(), $this->getName());
		$this->scheduler = new TaskScheduler($this->getName());
	}

	public function getName() : string{
		return "eval()'d code";
	}

	public static function eval(string $code) : void{
		self::getInstance()->evalInternal($code);
	}

	private function evalInternal(string $code) : void{
		eval($code);
	}

	public function getDataFolder() : string{
		return $this->dataFolder;
	}

	public function getLogger() : AttachableLogger{
		return $this->logger;
	}

	public function getServer() : Server{
		return $this->server;
	}

	public function getScheduler() : TaskScheduler{
		return $this->scheduler;
	}
}