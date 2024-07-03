<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox\fakeplugin;

use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;

final readonly class FakePluginLoader implements PluginLoader{

	public function __construct(){}

	public function canLoadPlugin(string $path) : bool{
		return true;
	}

	public function loadPlugin(string $file) : void{}

	public function getPluginDescription(string $file) : ?PluginDescription{
		return null;
	}

	public function getAccessProtocol() : string{
		return "";
	}
}
