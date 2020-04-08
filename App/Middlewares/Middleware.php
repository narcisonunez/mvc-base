<?php

namespace App\Middlewares;

use Core\Middlewares\TrimMiddleware;

class Middleware
{
	public static $globals = [
		// Default global middlewares
		TrimMiddleware::class,

		// Add your global middlewares here

	];

	public static $routes = [
		// "trim" => TrimMiddleware::class
	];
}
