<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\fakeplugin;

use pocketmine\plugin\ResourceProvider;

class FakeResourceProvider implements ResourceProvider{

	public function getResource(string $filename){
		return null;
	}

	public function getResources() : array{
		return [];
	}
}