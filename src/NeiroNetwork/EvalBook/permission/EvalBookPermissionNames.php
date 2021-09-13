<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\permission;

final class EvalBookPermissionNames{

	public const GROUP_OPERATOR = "evalbook.group.operator";
	public const GROUP_EVERYONE = "evalbook.group.everyone";

	public const EXECUTE_EVALBOOK = "evalbook.exec.evalbook";
	public const EXECUTE_CODEBOOK = "evalbook.exec.codebook";
	public const EXECUTE_CODEBOOK_OP = "evalbook.exec.codebook.op";
	public const EXECUTE_COODEBOOK_EVERYONE = "evalbook.exec.codebook.everyone";

	public const COMMAND_RELOAD = "evalbook.command.reload";
	public const COMMAND_NEW = "evalbook.command.new";
	public const COMMAND_SAVE = "evalbook.command.save";
	public const COMMAND_LOAD = "evalbook.command.load";
	public const COMMAND_PERM = "evalbook.command.perm";
	public const COMMAND_EXEC = "evalbook.command.exec";
}