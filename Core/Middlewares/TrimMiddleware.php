<?php

namespace Core\Middlewares;

class TrimMiddleware implements MiddlewareContract
{
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
