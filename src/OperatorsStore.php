<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\utils\Path;
use pocketmine\utils\Config;

final class OperatorsStore{

	private static Config $operators;

	public static function load(string $dataFolder) : void{
		self::$operators = new Config(Path::join($dataFolder, "allowlist.txt"), Config::ENUM);
	}

	public static function reload() : void{
		self::$operators->reload();
	}

	/** @return string[] */
	public static function getNames() : array{
		return self::$operators->getAll(true);
	}

	public static function exists(string $name) : bool{
		return self::$operators->exists($name, true);
	}
}
