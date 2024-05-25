<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\Main as EvalBook;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

final class EvalBookOperators{
	use SingletonTrait;

	private readonly Config $operators;

	public function __construct(){
		$this->operators = new Config(Path::join(EvalBook::getDataFolderPath(), "allowlist.txt"), Config::ENUM);
	}

	public function reload() : void{
		$this->operators->reload();
	}

	/**
	 * @return string[]
	 */
	public function getNames() : array{
		return $this->operators->getAll(true);
	}

	public function exists(string $name) : bool{
		return $this->operators->exists($name, true);
	}
}
