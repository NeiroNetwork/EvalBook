<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

final readonly class EvalBookPermissionNames{

	public const GROUP_OPERATOR = "evalbook.group.operator";

	public const COMMAND = "evalbook.command";
	public const COMMAND_RELOAD = "evalbook.command.reload";
	public const COMMAND_PERM = "evalbook.command.perm";
	public const COMMAND_NAME = "evalbook.command.name";
	public const COMMAND_GET = "evalbook.command.get";
	public const COMMAND_GIVE = "evalbook.command.give";

	public const BYPASS_BOOK_SOFT_LIMIT = "evalbook.bypass.book_soft_limit";
}
