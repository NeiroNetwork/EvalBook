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
		// あらかじめ存在しそうなクラスを検索して(ReflectionClassで参照させて)読み込む
		$recursiveDirectoryIterator = new \RecursiveDirectoryIterator(\pocketmine\PATH, \FilesystemIterator::SKIP_DOTS);
		$recursiveIteratorIterator = new \RecursiveIteratorIterator($recursiveDirectoryIterator, \RecursiveIteratorIterator::CHILD_FIRST);
		/** @var \RecursiveDirectoryIterator $file */
		foreach($recursiveIteratorIterator as $file){
			// PHPファイルである
			if($file->isFile() && strtolower($file->getExtension()) === "php"){
				$fileContents = file_get_contents($file->getPathname());
				// 「namespace ～;」が含まれるか検索
				if(preg_match("/namespace [0-9a-zA-Z_\\\]+;/", $fileContents, $matches) !== 1){
					continue;
				}
				$namespace = substr($matches[0], 10, -1);
				// 「class ～」が含まれるか検索
				if(preg_match_all("/class .+/", $fileContents, $matches) == 0){	// 0 or false
					continue;
				}
				foreach($matches[0] as $classString){
					// クラス名に使われなさそうな文字列が含まれていない
					if(preg_match("/[^0-9a-zA-Z_ \\\{},]/", $classString) === 0){
						preg_match("/class [0-9a-zA-Z_]+/", $classString, $matches2);
						try{
							new \ReflectionClass("$namespace\\" . substr($matches2[0], 6));
						}catch(\ReflectionException){
						}
					}
				}
			}
		}

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