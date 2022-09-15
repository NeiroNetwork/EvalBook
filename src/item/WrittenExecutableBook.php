<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\item;

use pocketmine\item\WritableBook;
use pocketmine\item\WrittenBook;
use pocketmine\player\Player;

final class WrittenExecutableBook extends ExecutableBook{

	public static function create(WritableBook $before, WrittenBook $after, Player $author = null) : WrittenBook{
		if($author !== null){
			$after->setAuthor($author->getName());
		}
		$after->setCustomName($after->getTitle())->setLore($before->getLore());
		$after->getNamedTag()->setByte("EvalBook", 1);
		return $after;
	}
}