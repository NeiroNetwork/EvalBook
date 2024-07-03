<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use CortexPE\Commando\args\TextArgument;
use pocketmine\command\CommandSender;
use pocketmine\permission\PermissionManager;

final class PermissionStringArgument extends TextArgument{

    public function canParse(string $testString, CommandSender $sender): bool
    {
        return parent::canParse($testString, $sender) && !is_null(PermissionManager::getInstance()->getPermission($testString));
    }
}
