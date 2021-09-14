<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

class CodeSense{

	public static function injectImport(string $code) : string{
		$importString = "";
		foreach(Imports::get() as $class){
			if(!str_contains($code, $class)){
				$importString .= "use {$class};";
			}
		}
		return $importString . $code;
	}
}