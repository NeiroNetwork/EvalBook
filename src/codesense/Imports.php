<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

final class Imports{

	/** @var string[] */
	private static array $importClasses = [];

	public static function get() : array{
		empty(self::$importClasses) && self::generate();
		return self::$importClasses;
	}

	public static function generate() : void{
		$phpFiles = array_merge(
			self::listPhpFiles(\pocketmine\PATH . "src"),
			self::listPhpFiles(\pocketmine\PATH . "vendor"),
		);

		$definedClasses = [];
		foreach($phpFiles as $pathname){
			$classes = self::listClasses(\PhpToken::tokenize(file_get_contents($pathname), TOKEN_PARSE));
			array_push($definedClasses, ...$classes);
		}

		$classesByName = [];
		foreach($definedClasses as $class){
			// グローバスな名前空間にいるクラスは読み込まない (エラーが出る)
			if(!str_contains($class, "\\")) continue;

			$name = array_reverse(explode("\\", $class))[0];
			$classesByName[$name][] = $class;
		}

		// 同じ名前を持つクラスからランダムで一つのクラスを選ぶ
		self::$importClasses = array_map(fn($classes) => $classes[array_rand($classes)], $classesByName);
	}

	/** @return string[] */
	private static function listPhpFiles(string $search) : array{
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
				T_CLASS, T_INTERFACE, T_TRAIT => ($next = $tokens[$key + 1])->id === T_STRING && $classes[] = $namespace . $next->text,
				default => null,
			};
		}

		return $classes;
	}
}
