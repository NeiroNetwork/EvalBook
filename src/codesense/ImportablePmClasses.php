<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use CallbackFilterIterator;
use FilesystemIterator;
use ParseError;
use PhpToken;
use pocketmine\utils\SingletonTrait;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class ImportablePmClasses{
	use SingletonTrait;

	/** @noinspection PhpFullyQualifiedNameUsageInspection */
	private const PRIORITIZED_IMPORTS = [
		\pocketmine\Server::class,
		\pocketmine\player\GameMode::class,
		\pocketmine\network\mcpe\protocol\serializer\PacketSerializer::class,
		\Ramsey\Uuid\Uuid::class,
		\Ramsey\Uuid\UuidInterface::class,
	];

	/** @var array<string, string> */
	public readonly array $classList;

	public function __construct(){
		$classesByName = [];
		foreach($this->scanPmClasses() as $class){
			// グローバルな名前空間にいるクラスは読み込まない (エラーが出る)
			if(str_contains($class, "\\")){
				$name = array_reverse(explode("\\", $class))[0];
				$classesByName[$name][] = $class;
			}
		}

		// 同じ名前が複数存在するクラスはインポートしない
		$classesByName = array_filter($classesByName, fn($classes) => count($classes) < 2);

		// 優先して読み込むクラスをねじ込む
		foreach(self::PRIORITIZED_IMPORTS as $import){
			$name = array_reverse(explode("\\", $import))[0];
			$classesByName[$name][] = $import;
		}

		array_walk($classesByName, fn(array &$classes) => $classes = $classes[0]);
		$this->classList = $classesByName;
	}

	/**
	 * @return string[]
	 */
	private function scanPmClasses() : array{
		/** @noinspection PhpFullyQualifiedNameUsageInspection */
		$phpFiles = array_merge(self::listPhpFiles(\pocketmine\PATH . "src"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/adhocore"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/brick"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/pocketmine"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/ramsey"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/symfony"),);

		$definedClasses = [];
		foreach($phpFiles as $pathname){
			try{
				$tokens = PhpToken::tokenize(file_get_contents($pathname), TOKEN_PARSE);
			}catch(ParseError){
				continue;
			}
			array_push($definedClasses, ...self::listClasses($tokens));
		}

		return $definedClasses;
	}

	/** @return string[] */
	private function listPhpFiles(string $search) : array{
		if(!file_exists($search)){
			return [];
		}
		$iterator = new RecursiveDirectoryIterator($search, FilesystemIterator::SKIP_DOTS);
		$iterator = new CallbackFilterIterator(new RecursiveIteratorIterator($iterator), fn(SplFileInfo $info) => $info->isFile() && $info->getExtension() === "php",);
		return array_keys(iterator_to_array($iterator));
	}

	/**
	 * @param PhpToken[] $tokens
	 *
	 * @return string[]
	 */
	private function listClasses(array $tokens) : array{
		/** @var PhpToken[] $tokens */
		$tokens = array_values(array_filter($tokens, fn(PhpToken $token) => !$token->isIgnorable()));

		$classes = [];
		$namespace = "";

		foreach($tokens as $key => $token){
			match ($token->id) {
				T_NAMESPACE => $namespace = match (($next = $tokens[$key + 1])->id) {
					T_NAME_QUALIFIED, T_STRING => $next->text . "\\",
					default => "",
				},
				T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM => ($next = $tokens[$key + 1])->id === T_STRING && $classes[] = $namespace . $next->text,
				default => null,
			};
		}

		return $classes;
	}

	/**
	 * @return array<string, string>
	 */
	public function getImportableClasses() : array{
		return $this->classList;
	}
}
