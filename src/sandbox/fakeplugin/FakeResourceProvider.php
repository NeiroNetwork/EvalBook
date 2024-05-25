<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox\fakeplugin;

use pocketmine\plugin\ResourceProvider;

final readonly class FakeResourceProvider implements ResourceProvider{

	public function getResource(string $filename) : null{
		return null;
	}

	public function getResources() : array{
		return [];
	}
}
