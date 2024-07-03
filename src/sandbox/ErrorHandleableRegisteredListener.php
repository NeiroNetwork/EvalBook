<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox;

use Closure;
use DaveRandom\CallbackValidator\CallbackType;
use DaveRandom\CallbackValidator\ParameterType;
use DaveRandom\CallbackValidator\ReturnType;
use pocketmine\event\Event;
use pocketmine\event\RegisteredListener;
use pocketmine\plugin\Plugin;
use pocketmine\timings\TimingsHandler;
use pocketmine\utils\Utils;
use Throwable;

final class ErrorHandleableRegisteredListener extends RegisteredListener{

	public function __construct(
		Closure $handler,
		int $priority,
		Plugin $plugin,
		bool $handleCancelled,
		TimingsHandler $timings,
		private ?Closure $exceptionHandler = null
	){
		parent::__construct($handler, $priority, $plugin, $handleCancelled, $timings);

		if(!is_null($exceptionHandler)){
			Utils::validateCallableSignature(new CallbackType(
				new ReturnType("bool"),
				new ParameterType("exception", Throwable::class)
			), $exceptionHandler);
		}
	}

	public function callEvent(Event $event) : void{
		try{
			parent::callEvent($event);
		}catch(Throwable $e){
			if(!is_null($this->exceptionHandler) && ($this->exceptionHandler)($e)){
				return;
			}
			throw $e;
		}
	}
}
