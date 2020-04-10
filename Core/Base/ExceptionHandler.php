<?php

namespace Core\Base;

class ExceptionHandler
{
	/**
	 * Global error handler
	 * 
	 * @param int $code
	 * @param string $description
	 * @param string $file
	 * @param string $line 
	 */
	public static function errorHandler($code, $description, $file, $line)
	{
		http_response_code(500);

		$message = "Error: $code\n\n";
		$message .= "Message: $description\n\n";
		$message .= "File: $file\n\n";
		$message .= "Line: $line";

		if (env_value("ENVIRONMENT") != "local") {
			return view("errors.500");
		}

		dd($message);
	}

	/**
	 * Global exception handler
	 * @param $exception
	 */
	public static function exceptionHandler($exception)
	{
		$code = $exception->getCode();

		switch ($code) {
			case 403:
			case 404:
			case 500: {
					http_response_code($code);
					if (env_value("ENVIRONMENT") != "local") {
						return view("errors.$code");
					}
					dd($exception);
					return;
				}
		}
	}
}
