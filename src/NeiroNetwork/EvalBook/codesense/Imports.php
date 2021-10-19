<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use NeiroNetwork\EvalBook\Main;
use pocketmine\entity\Attribute;
use pocketmine\player\GameMode;
use pocketmine\utils\SingletonTrait;
use Ramsey\Uuid\Uuid;

class Imports{
	use SingletonTrait;

	public const SINGLE_IMPORT_LIST = [
		Uuid::class,
		Attribute::class,
		GameMode::class,
	];

	public static function get() : array{
		return self::getInstance()->getImports();
	}

	/** @var string[] */
	private array $importClasses = [
		Main::class,
	];

	public function __construct(){
		$classes = [];

		$classMap = require \pocketmine\PATH . "vendor/composer/autoload_classmap.php";
		foreach($classMap as $class => $_){
			if($this->checkIfNeed($class)){
				$explodedClass = explode("\\", $class);
				$classes[end($explodedClass)][] = $class;
			}
		}

		foreach($classes as $list){
			if(count($list) > 1){
				foreach($list as $class){
					if(in_array($class, self::SINGLE_IMPORT_LIST, true)){
						$this->importClasses[] = $class;
						continue 2;
					}
				}
			}
			$this->importClasses[] = reset($list);
		}
	}

	private function checkIfNeed(string $class) : bool{
		return str_starts_with($class, "pocketmine")
			|| str_contains($class, "PathUtil")
			|| str_contains($class, "Uuid");
	}

	public function getImports() : array{
		return $this->importClasses;
	}
}