<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

final class EvalBookPermissionNames{

	public const GROUP_OPERATOR = "evalbook.group.operator";

	public const EXECUTE_DEFAULT = "evalbook.execute.default";
	public const EXECUTE_OP = "evalbook.execute.op";
	public const EXECUTE_EVERYONE = "evalbook.execute.everyone";

	public const COMMAND = "evalbook.command";
	public const COMMAND_RELOAD = "evalbook.command.reload";
	public const COMMAND_NEW = "evalbook.command.new";
	public const COMMAND_PERM = "evalbook.command.perm";
	public const COMMAND_CUSTOM_NAME = "evalbook.command.custom_name";
}
