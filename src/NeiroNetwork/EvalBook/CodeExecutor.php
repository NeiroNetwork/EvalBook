<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\fakeplugin\FakePluginBase;

class CodeExecutor extends FakePluginBase{

	private static ?self $instance = null;

	public static function getInstance() : self{
		if(self::$instance === null){
			self::$instance = self::make();
		}
		return self::$instance;
	}

	public static function eval(string $code) : void{
		self::getInstance()->evalInternal($code);
	}

	private function evalInternal(string $code) : void{
		eval($code);
	}
}