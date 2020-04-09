<?php

namespace Core\Base;

class ExceptionHandler
{
	public function errorHandler($errNo, $errStr, $errFile, $errLine)
	{
		dd($errNo, $errStr, $errFile, $errLine);
	}

	public static function exceptionHandler($exception)
	{
		$code = $exception->getCode();

		if ($code == 500 || $code == 404) {
			return view("errors.$code");
		}
	}
}
