<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use pocketmine\Server;

final class CrashReportDisabler{

	public static function disableAutoReport(Server $server) : void{
		$group = $server->getConfigGroup();

		$enabled = $group->getConfigBool("auto-report.enabled");
		$host = $group->getConfigString("auto-report.host");
		if($enabled && str_ends_with($host, "pmmp.io")){
			$propertyCache = (new \ReflectionClass($group))->getProperty("propertyCache");
			$propertyCache->setAccessible(true);
			$propertyCache->setValue($group, ["auto-report.enabled" => false]);
		}
	}
}
