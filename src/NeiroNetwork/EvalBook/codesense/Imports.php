<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use pocketmine\utils\SingletonTrait;

class Imports{
	use SingletonTrait;

	public static function get() : array{
		return self::getInstance()->getImports();
	}

	/** @var string[] */
	private array $importClasses = [];

	public function __construct(){
		$classes = [];

		foreach(get_declared_classes() as $class){
			$reflection = new \ReflectionClass($class);
			if($reflection->inNamespace() && !$reflection->isAnonymous()){
				$classes[$reflection->getShortName()][] = $class;
			}
		}

		foreach($classes as $classList){
			$this->importClasses[] = reset($classList);
		}
	}

	public function getImports() : array{
		return $this->importClasses;
	}
}