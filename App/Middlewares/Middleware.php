<?php

namespace App\Middlewares;

use Core\Middlewares\TrimMiddleware;

class Middleware
{
	public static $globals = [
		TrimMiddleware::class
	];
}
