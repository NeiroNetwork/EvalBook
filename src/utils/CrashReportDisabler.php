<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use pocketmine\Server;

final class CrashReportDisabler{

	public static function disableAutoReport(Server $server) : void{
		$group = $server->getConfigGroup();

		$propertyCache = (new \ReflectionClass($group))->getProperty("propertyCache");
		$propertyCache->setAccessible(true);
		$propertyCache->setValue($group, ["auto-report.enabled" => false]);
	}
}
