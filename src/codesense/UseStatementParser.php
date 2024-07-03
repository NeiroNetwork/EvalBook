<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook\codesense;

use Generator;
use ParseError;
use PhpToken;

final readonly class UseStatementParser{

	/**
	 * @return array<string, string>
	 * @throws ParseError
	 */
	public static function parse(string $code) : array{
		$tokens = PhpToken::tokenize($code, TOKEN_PARSE);
		$tokens = array_values(array_filter($tokens, fn(PhpToken $token) => !$token->isIgnorable()));

		$uses = [];

		foreach(self::extractUseStatementTokens($tokens) as $statement){
			$name = "";
			$alias = null;
			do{
				/** @var PhpToken $token */
				$token = current($statement);
				if($token->is([T_CONST, T_FUNCTION])){
					// Ignore the use statement that contain T_CONST or T_FUNCTION
					continue 2;
				}
				$token->is(T_AS) ? $alias = next($statement)->text : $name .= $token->text;
			}while(next($statement));

			$alias ??= array_reverse(explode("\\", $name))[0];
			$uses[$alias] = $name;
		}

		return $uses;
	}

	/**
	 * @return Generator<PhpToken[]>
	 */
	private static function extractUseStatementTokens(array $tokens) : Generator{
		while($token = current($tokens)){
			if($token->is(T_USE)){
				/** @var PhpToken[] $statement */
				$statement = [];
				/** @var PhpToken[] $base */
				$base = [];
				while(($token = next($tokens)) && !$token->is(';')){
					if($token->is('{')){
						$base = $statement;
						$statement = [];
					}elseif($token->is([',', '}']) && !empty($statement)){
						yield array_merge($base, $statement);
						$statement = [];
					}else{
						$statement[] = $token;
					}
				}
				if(!empty($statement)){
					yield $statement;
				}
			}

			// Skip class, interface, trait, enum, function declarations
			if($token->is([T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM, T_FUNCTION])){
				/** @noinspection PhpStatementHasEmptyBodyInspection */
				while(!next($tokens)->is(['{', T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES])){
					// Skip to the first opening curly brace
				}
				$level = 1;
				while($level > 0 && $token = next($tokens)){
					$token->is(['{', T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES]) && $level++;
					$token->is('}') && $level--;
				}
			}

			next($tokens);
		}
	}
}
