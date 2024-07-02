<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox;

use Closure;
use DaveRandom\CallbackValidator\CallbackType;
use DaveRandom\CallbackValidator\ParameterType;
use DaveRandom\CallbackValidator\ReturnType;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\utils\Utils;
use Throwable;

final class ErrorHandleableTaskScheduler extends TaskScheduler{

	public function __construct(
		?string $owner = null,
		private ?Closure $exceptionHandler = null
	){
		parent::__construct($owner);
		
		if(!is_null($exceptionHandler)){
			Utils::validateCallableSignature(new CallbackType(
				new ReturnType("bool"),
				new ParameterType("exception", Throwable::class)
			), $exceptionHandler);
		}
	}

	public function mainThreadHeartbeat(int $currentTick) : void{
		try{
			parent::mainThreadHeartbeat($currentTick);
		}catch(Throwable $e){
			if(!is_null($this->exceptionHandler) && ($this->exceptionHandler)($e)){
				return;
			}
			throw $e;
		}
	}
}
