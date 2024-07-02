<?php

//declare(strict_types=1);

namespace NeiroNetwork\EvalBook\sandbox;

use AllowDynamicProperties;
use NeiroNetwork\EvalBook\LibEvalBook;
use NeiroNetwork\EvalBook\sandbox\fakeplugin\FakePluginBase;
use pocketmine\event\HandlerListManager;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use Throwable;

#[AllowDynamicProperties]
final class SandboxPlugin extends FakePluginBase implements Listener{
	use SingletonTrait;

	private static function make() : self{
		$plugin = self::create("EvalBookSandbox", "0.0.1");
		$plugin->getServer()->getPluginManager()->enablePlugin($plugin);
		return $plugin;
	}

	private TaskScheduler $scheduler;

	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider){
		$this->scheduler = new ErrorHandleableTaskScheduler($description->getFullName(), function(Throwable $e) : bool{
			LibEvalBook::notifyException($e);
			return true;
		});
		parent::__construct($loader, $server, $description, $dataFolder, $file, $resourceProvider);
	}

	public function getScheduler() : TaskScheduler{
		return $this->scheduler;
	}

	public function eval(string $code, bool $flushOutput = true) : string{
		if($flushOutput){
			$output = "";
			ob_start(function(string $buffer) use (&$output){
				$output .= $buffer;
				return $buffer;
			}, 1);
		}else{
			ob_start();
		}

		eval($code);

		if($flushOutput){
			ob_end_flush();
		}else{
			$output = ob_get_clean();
		}

		$this->reRegisterListeners();

		return $output;
	}

	private function reRegisterListeners() : void{
		foreach(HandlerListManager::global()->getAll() as $handlerList){
			foreach($handlerList->getListenerList() as $registeredListener){
				if($registeredListener->getPlugin() === $this && !$registeredListener instanceof ErrorHandleableRegisteredListener){
					$handlerList->unregister($registeredListener);
					$listener = new ErrorHandleableRegisteredListener(
						$registeredListener->getHandler(),
						$registeredListener->getPriority(),
						$registeredListener->getPlugin(),
						$registeredListener->isHandlingCancelled(),
						(new ReflectionClass($registeredListener))->getProperty("timings")->getValue($registeredListener),
						function(Throwable $e) : bool{
							LibEvalBook::notifyException($e);
							return true;
						}
					);
					$handlerList->register($listener);
				}
			}
		}
	}
}
