<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

final class Imports{

	private const PRIORITIZED_IMPORTS = [
		\pocketmine\Server::class,
		\pocketmine\player\GameMode::class,
		\pocketmine\network\mcpe\protocol\serializer\PacketSerializer::class,
		\Ramsey\Uuid\Uuid::class,
		\Ramsey\Uuid\UuidInterface::class
	];

	/** @var string[] */
	private static array $importClasses = [];

	public static function get() : array{
		empty(self::$importClasses) && self::generate();
		return self::$importClasses;
	}

	public static function generate() : void{
		$phpFiles = array_merge(
			self::listPhpFiles(\pocketmine\PATH . "src"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/adhocore"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/brick"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/fgrosse"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/netresearch"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/pocketmine"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/ramsey"),
			self::listPhpFiles(\pocketmine\PATH . "vendor/symfony"),
		);

		$definedClasses = [];
		foreach($phpFiles as $pathname){
			try{
				$tokens = \PhpToken::tokenize(file_get_contents($pathname), TOKEN_PARSE);
			}catch(\ParseError){ continue; }
			array_push($definedClasses, ...self::listClasses($tokens));
		}

		$classesByName = [];
		foreach($definedClasses as $class){
			// グローバルな名前空間にいるクラスは読み込まない (エラーが出る)
			if(!str_contains($class, "\\")) continue;

			$name = array_reverse(explode("\\", $class))[0];
			$classesByName[$name][] = $class;
		}

		// 同じ名前が複数存在するクラスはインポートしない
		$classesByName = array_filter($classesByName, fn($classes) => count($classes) < 2);
		// 優先して読み込むクラスをねじ込む
		foreach(self::PRIORITIZED_IMPORTS as $import) $classesByName[][] = $import;

		self::$importClasses = array_map(fn($classes) => $classes[0], $classesByName);
	}

	/** @return string[] */
	private static function listPhpFiles(string $search) : array{
		if(!file_exists($search)) return [];
		$iterator = new \RecursiveDirectoryIterator($search, \FilesystemIterator::SKIP_DOTS);
		$iterator = new \CallbackFilterIterator(
			new \RecursiveIteratorIterator($iterator),
			fn(\SplFileInfo $info) => $info->isFile() && $info->getExtension() === "php"
		);
		return array_keys(iterator_to_array($iterator));
	}

	/** @param \PhpToken[] $tokens */
	private static function listClasses(array $tokens) : array{
		/** @var \PhpToken[] $tokens */
		$tokens = array_values(array_filter($tokens, fn(\PhpToken $token) => !$token->isIgnorable()));

		$classes = [];
		$namespace = "";

		foreach($tokens as $key => $token){
			match($token->id){
				T_NAMESPACE => $namespace = match(($next = $tokens[$key + 1])->id){
					T_NAME_QUALIFIED, T_STRING => $next->text . "\\",
					default => "",
				},
				T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM => ($next = $tokens[$key + 1])->id === T_STRING && $classes[] = $namespace . $next->text,
				default => null,
			};
		}

		return $classes;
	}
}
