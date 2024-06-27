<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use DateTimeImmutable;
use DateTimeInterface;
use NeiroNetwork\EvalBook\codesense\CodeSense;
use NeiroNetwork\EvalBook\item\EvalBookEnchantment;
use NeiroNetwork\EvalBook\Main as EvalBook;
use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use NeiroNetwork\EvalBook\sandbox\SandboxPlugin;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\WritableBook;
use pocketmine\item\WritableBookBase;
use pocketmine\item\WritableBookPage;
use pocketmine\item\WrittenBook;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;
use Symfony\Component\Filesystem\Path;
use Throwable;

final readonly class LibEvalBook{

	public static function isEvalBook(Item $item) : bool{
		return $item instanceof WritableBookBase
			&& $item->getNamedTag()->getByte("EvalBook", 0) === 3
			&& !is_null(self::getBookPermission($item));
	}

	public static function getBookPermission(Item $item) : ?Permission{
		return PermissionManager::getInstance()->getPermission($item->getLore()[0] ?? "");
	}

	public static function hasPermission(WritableBookBase $book, CommandSender $sender) : bool{
		$permission = self::getBookPermission($book);
		return !is_null($permission) && $sender->hasPermission($permission);
	}

	public static function signEvalBook(WritableBook $old, WrittenBook $new, CommandSender $signer) : WrittenBook{
		$new->setAuthor($signer->getName())
			->setLore($old->getLore())
			->setCustomName($new->getTitle());
		$new->getNamedTag()->setByte("EvalBook", 3);

		return $new;
	}

	public static function toWritableBook(WritableBookBase $base) : WritableBookBase{
		return self::createEmptyBook()
			->setCustomName($base->getCustomName())
			->setPages($base->getPages())
			->setLore($base->getLore());
	}

	public static function createEmptyBook() : WritableBookBase{
		$book = VanillaItems::WRITABLE_BOOK()
			->setCustomName("EvalBook")
			->addEnchantment(new EnchantmentInstance(EvalBookEnchantment::getInstance()))
			->setLore([EvalBookPermissions::ROOT_OPERATOR]);
		$book->getNamedTag()->setByte("EvalBook", 3);

		return $book;
	}

	public static function executeBook(WritableBookBase $book, ?CommandSender $executor = null) : void{
		$pages = array_map(fn(WritableBookPage $page) : string => $page->getText(), $book->getPages());
		$pages = array_filter($pages, fn(string $text) : bool => trim($text) !== "");
		$code = implode(PHP_EOL, $pages);

		self::log($code, $executor);

		$code = CodeSense::preprocess($code, $executor);

		try{
			$output = SandboxPlugin::getInstance()->eval($code);
			/** @see TextFormat::clean() */
			$output = str_replace("\x1b", "", preg_replace("/\x1b[\\(\\][[0-9;\\[\\(]+[Bm]/u", "", mb_scrub($output, 'UTF-8')));
			if($output !== ""){
				$executor?->sendMessage($output);
			}
		}catch(Throwable $exception){
			/**
			 * メモ: fatal error はどうあがいてもキャッチできない
			 * 例えば、クラスの間違った継承、同じ名前の関数やクラスを2回以上定義したり…
			 */

			$traces = [];
			foreach($exception->getTrace() as $trace){
				$traces[] = $trace;
				if($trace["class"] ?? null === SandboxPlugin::class && $trace["function"] === "eval"){
					break;
				}
			}

			$message = implode("\n", Utils::printableExceptionInfo($exception, $traces));
			$executor?->sendMessage(TextFormat::RED . $message);
		}
	}

	private static function log(string $evaldCode, ?CommandSender $executor = null) : void{
		echo $evaldCode . PHP_EOL;
		$path = Path::join(EvalBook::getPlugin()->getDataFolder(), "evals.log");
		$message = implode(",", [
			(new DateTimeImmutable())->format(DateTimeInterface::ATOM),
			$executor?->getName() ?? "null",
			base64_encode($evaldCode),
		]);
		file_put_contents($path, $message . PHP_EOL, FILE_APPEND);
	}
}
