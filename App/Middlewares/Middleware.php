<?php

namespace App\Middlewares;

use Core\Middlewares\TrimMiddleware;

class Middleware
{
	/**
	 * Middlewares that will be executed for every request
	 * @var array
	 */
	public static $globals = [
		// Default global middlewares
		TrimMiddleware::class,

		// Add your global middlewares here

	];

	/**
	 * Middlewares that can be use with the router middewares method
	 * @var array
	 */
	public static $routes = [
		// "alias" => MyOwnMiddleware::class
	];
}
