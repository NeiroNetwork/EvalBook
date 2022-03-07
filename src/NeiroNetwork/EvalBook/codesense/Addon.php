<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use pocketmine\command\CommandSender;

abstract class Addon {

	const ALLOWED_NAMESPACE = "[a-zA-z]";

	private static array $list = [];

	private string $name;

	public static function registerAddon(Addon $addon): void{
		self::$list[$addon::class] = $addon;
	}

	/**
	 * @param string $code
	 * 
	 * @return Addon[]
	 */
	public static function parseAddons(string &$code): array{
		$ns = self::ALLOWED_NAMESPACE;
		preg_match_all("/import {$ns}+;/", $code, $matchesAll);

		$addons = [];
		foreach($matchesAll as $matches){
			foreach($matches as $match){
				print_r("Matched: {$match}\n");
				$name = substr($match, 7);
				$name = substr($name, 0, -1);
				$found = self::searchAddon($name);
				if (count($found) > 0){
					$code = preg_replace("/import {$name};/", "", $code);
				}
				$addons = array_merge($addons, $found);
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
	 * @return Addon[]
	 */
	public static function searchAddon(string $name): array{
		$found = [];
		foreach(self::$list as $class => $addon){
			if ($name == $addon->getName()){
				$found[] = $addon;
			}
		}

		return $found;
	}

	public function __construct(){
		$this->name = (new \ReflectionClass($this))->getShortName();
	}

	protected function setName(string $name): void{
		$ns = self::ALLOWED_NAMESPACE;
		if (!preg_match_all("/{$ns}+/", $name)){
			throw new \Exception("cannot set name \"{$name}\"");
		}
		$this->name = $name;
	}

	public function getName(): string{
		return $this->name;
	}

	abstract public function onInject(string &$code, ?CommandSender $executor = null): void;
}