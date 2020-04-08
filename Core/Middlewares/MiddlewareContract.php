<?php

namespace Core\Middlewares;

interface MiddlewareContract
{
	public function execute($request);
}
