<?php

namespace Core\Middlewares;

interface MiddlewareContract
{
	/**
	 * Middleware logic
	 * @param Core\Base\Request
	 * 
	 * @return Core\Base\Request|false
	 */
	public function execute($request);
}
