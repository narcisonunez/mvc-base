<?php

namespace Core\Middlewares;

use Core\Middlewares\MiddlewareContract;

class VerifyCsrfToken implements MiddlewareContract
{
	/**
	 * Middleware logic
	 * @param Core\Base\Request
	 * 
	 * @return Core\Base\Request|false
	 */
	public function execute($request)
	{
		if ($request->method() !== "GET") {
			if (
				!empty($request->post("_csrf_token")) &&
				!empty($_SESSION["_session_id"]) &&
				$request->post("_csrf_token") == $_SESSION["_session_id"]
			) {
				return $request;
			}

			throw new \Exception("Missing token", 403);
		}

		return $request;
	}
}
