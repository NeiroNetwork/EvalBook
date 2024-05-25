<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox\fakeplugin;

use NeiroNetwork\EvalBook\Main as EvalBook;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\Server;
use Symfony\Component\Filesystem\Path;

abstract class FakePluginBase extends PluginBase{

	public static function create(string $name, string $version) : static{
		return new static(new FakePluginLoader(),
			Server::getInstance(),
			new PluginDescription(["name" => $name, "version" => $version, "main" => static::class, "api" => ["5.0.0"]]),
			Path::join(EvalBook::getDataFolderPath(), "sandbox"),
			uniqid("plugin_", true),
			new FakeResourceProvider());
	}
}
