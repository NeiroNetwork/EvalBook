<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\utils;

use pocketmine\VersionInfo;

class Path{

	public static function join(string ...$paths) : string{
		return match(VersionInfo::VERSION()->getMajor()){
			4 => \Webmozart\PathUtil\Path::join(...$paths),
			5 => \Symfony\Component\Filesystem\Path::join(...$paths),
		};
	}
}
