<?php

namespace App\Middlewares;

use Core\Middlewares\VerifyCsrfToken;
use Core\Middlewares\TrimMiddleware;

class Middleware
{
	/**
	 * Middlewares that will be executed for every request
	 * @var array
	 */
	public static $globals = [
		// Default global middlewares
		VerifyCsrfToken::class,
		TrimMiddleware::class,
		// Add your global middlewares here
	];

	/**
	 * Middlewares that can be use with the router middewares method
	 * @var array
	 */
	public static $routes = [
		// "trim" => TrimMiddleware::class,
	];
}
