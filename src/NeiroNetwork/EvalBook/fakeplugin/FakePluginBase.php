<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\fakeplugin;

use NeiroNetwork\EvalBook\Main;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\PluginLogger;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use Webmozart\PathUtil\Path;

class FakePluginBase implements Plugin{

	public static function make() : static{
		return new static(
			new FakePluginLoader(),
			Server::getInstance(),
			new FakePluginDescription(),
			Path::join(Main::getInstance()->getDataFolder(), "data_folder"),
			"",
			new FakeResourceProvider()
		);
	}

	private PluginLoader $loader;
	private Server $server;
	private PluginDescription $description;
	private string $dataFolder;
	private PluginLogger $logger;
	private TaskScheduler $scheduler;

	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider){
		$this->loader = $loader;
		$this->server = $server;
		$this->description = $description;
		$this->dataFolder = $dataFolder;

		if(!file_exists($this->dataFolder)){
			mkdir($this->dataFolder, 0777, true);
		}

		$this->logger = new PluginLogger($this->server->getLogger(), $this->getName());
		$this->scheduler = new TaskScheduler($this->getName());
	}

	public function getName() : string{
		return "eval()'d code";
	}

	public function getDataFolder() : string{
		return $this->dataFolder;
	}

	public function getLogger() : \AttachableLogger{
		return $this->logger;
	}

	public function getServer() : Server{
		return $this->server;
	}

	public function getScheduler() : TaskScheduler{
		return $this->scheduler;
	}

	public function isEnabled() : bool{
		return true;
	}

	public function onEnableStateChange(bool $enabled) : void{
	}

	public function getDescription() : PluginDescription{
		return $this->description;
	}

	public function getPluginLoader() : PluginLoader{
		return $this->loader;
	}
}