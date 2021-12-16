<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use NeiroNetwork\EvalBook\Main;
use pocketmine\utils\SingletonTrait;

class Imports{
	use SingletonTrait;

	public const SINGLE_IMPORT_LIST = [
		'Ramsey\Uuid\Uuid',
		'pocketmine\entity\Attribute',
		'pocketmine\player\GameMode',
	];

	public static function get() : array{
		return self::getInstance()->getImports();
	}

	/** @var string[] */
	private array $importClasses = [];

	public function __construct(){
		$classes = [];

		/*
		 * FIXME: 全てインポートしようとするとプラグインのクラスと名前がかぶってしまいインポート出来ない
		 * 解決策1: checkIfNeed() を使う → なるべく多くのクラスをインポートしたい
		 * 解決策2: プラグインのクラスを識別する → どうやって？
		 */

		foreach(get_declared_classes() as $class){
			$reflection = new \ReflectionClass($class);
			if($reflection->inNamespace() && !$reflection->isAnonymous()){
				$classes[$reflection->getShortName()][] = $class;
			}
		}

		foreach($classes as $className => $classList){
			if(count($classList) > 1){
				foreach($classList as $class){
					if(in_array($class, self::SINGLE_IMPORT_LIST, true)){
						$this->importClasses[] = $class;
						continue 2;
					}
				}
				Main::getInstance()->getLogger()->debug("found " . count($classList) . " classes with '$className'");
			}
			$this->importClasses[] = reset($classList);
		}
	}

	public function getImports() : array{
		return $this->importClasses;
	}
}