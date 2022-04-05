<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use NeiroNetwork\EvalBook\item\ExecutableBook;
use pocketmine\command\CommandSender;
use pocketmine\item\WritableBookBase;
use pocketmine\item\WrittenBook;
use pocketmine\utils\Utils;

class CodeAddon extends Addon {

	#todo: EvalBookに別のEvalBookをインポートできる機能を追加

	#todo: AddonShop を追加して vscodeの拡張機能みたいなやつにする

	protected string $code;
	protected string $author;
	protected string $title;

	public static function fromBook(WrittenBook $book): CodeAddon{
		if (!self::canBeAddon($book)){
			throw new \Exception("this book cannot be addon");
		}
		$code = ExecutableBook::parseBookCode($book);
		$addon = new self($code, $book->getAuthor());
		return $addon;
	}

	public static function canBeAddon(WrittenBook $book): bool{
		return self::isValid($book->getTitle(), ExecutableBook::parseBookCode($book), $book->getAuthor());
	}

	public static function isValid(string $title, string $code, string $author): bool{
		if (mb_strlen($code, "utf-8") <= 2){
			return false;
		}

		if (!Addon::isValidName($title)){
			return false;
		}

		return true;
	}

	public function __construct(string $title, string $code, string $author = "unknown"){
		if (self::isValid($title, $code, $author)){
			throw new \Exception("not valid");
		}

		$this->title = $title;
		$this->code = $code;
		$this->author = $author;

		$this->setName($title);
	}

	public function onInject(string &$code, ?CommandSender $executor = null): void{
		$code .= $this->code;
	}
}