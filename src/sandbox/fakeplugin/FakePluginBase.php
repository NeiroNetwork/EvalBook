<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox\fakeplugin;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\Server;
use Symfony\Component\Filesystem\Path;

abstract class FakePluginBase extends PluginBase{

	public static function create(string $name, string $version) : static{
		$server = Server::getInstance();
		$parent = $server->getPluginManager()->getPlugin("EvalBook");

		return new static(new FakePluginLoader(),
			$server,
			new PluginDescription(["name" => $name, "version" => $version, "main" => static::class, "api" => ["5.0.0"]]),
			Path::join($parent->getDataFolder(), "sandbox"),
			uniqid("plugin_", true),
			new FakeResourceProvider());
	}
}
