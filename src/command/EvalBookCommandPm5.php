<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\command;

use NeiroNetwork\EvalBook\permission\EvalBookPermissionNames;

class EvalBookCommandPm5 extends EvalBookCommandPm4{

	// TODO: REMOVE THIS 💩 PATCH FOR PM5
	public function getPermission(){
		return [EvalBookPermissionNames::COMMAND];
	}
}
