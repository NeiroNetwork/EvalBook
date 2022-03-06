<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

abstract class Addon {

	private static array $list = [];

	protected string $name;

	public static function registerAddon(Addon $addon): void{
		self::$list[$addon::class] = $addon;
	}

	/**
	 * @param string $code
	 * 
	 * @return Addon[]
	 */
	public static function detectAddons(string $code): array{
		preg_match("/^import [a-zA-z]+;/", $code, $matches);
		$addons = [];
		foreach($matches as $match){
			$class = substr($match, 5);
			$class = substr($class, -1);
			$addon = self::$list[$class] ?? null;
			if ($addon instanceof Addon){
				$addons[] = $addon;
			}
		}

		return $addons;
	}

	/**
	 * @return array{class: string, addon: Addon}
	 */
	public static function getAllAddon(): array{
		return self::$list;
	}

	/**
	 * @param string $name
	 * 
	 * @return string[]
	 */
	public static function searchAddon(string $name): array{
		$found = [];
		foreach(self::$list as $class => $addon){
			if ($name == $addon->getName()){
				$found[] = $class;
			}
		}

		return $found;
	}

	public function __construct(){
		$this->name = (new \ReflectionClass($this))->getShortName();
	}

	protected function getName(): string{
		return $this->name;
	}

	abstract public function onInject(string &$code): void;
}