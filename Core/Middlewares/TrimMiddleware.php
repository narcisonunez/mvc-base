<?php

namespace Core\Middlewares;

class TrimMiddleware implements MiddlewareContract
{
	/**
	 * Middleware logic
	 * @param Core\Base\Request
	 * 
	 * @return Core\Base\Request|false
	 */
	public function execute($request)
	{
		foreach ($_GET as $key => $value) {
			$request->get[$key] = trim($value);
		}

		foreach ($_POST as $key => $value) {
			$request->post[$key] = trim($value);
		}

		return $request;
	}
}
