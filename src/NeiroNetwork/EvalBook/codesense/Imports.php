<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use NeiroNetwork\EvalBook\Main;
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

		foreach($classes as $className => $classList){
			if(count($classList) > 1){
				Main::getInstance()->getLogger()->debug("found " . count($classList) . " classes with '$className'");
				array_map(fn(string $class) => Main::getInstance()->getLogger()->debug("    $class"), $classList);
			}
			$this->importClasses[] = reset($classList);
		}
	}

	public function getImports() : array{
		return $this->importClasses;
	}
}