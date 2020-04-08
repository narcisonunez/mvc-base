<?php

namespace Core\Base;

use App\Middlewares\Middleware;
use Core\Base\Request;

class HttpHandler
{

	/**
	 * Resolve the current request and execute the corresponding actions
	 * 
	 * @param Core\Base\Request $request
	 */
	public function execute(Request $request)
	{
		$params = $request->routeParams;
		$routeHandler = $request->routeHandler;

		$controller = "App\\Controllers\\" . $routeHandler['controller'];

		if (!class_exists($controller)) {
			echo "Controller <strong>" . $routeHandler['controller'] . "</strong> doesn't exists.";
			return;
		}

		$request = $this->applyGlobalMiddlewares($request);

		$controller = new $controller;
		$controller->request($request);

		$action = $routeHandler['action'];
		$actionFilters = isset($routeHandler['actionFilters']) ? $routeHandler['actionFilters'] : [];

		if (method_exists($controller, $action)) {
			if (isset($actionFilters["before"])) {
				$this->applyFilters($controller, $actionFilters["before"], $params);
			}

			$viewHTML = $controller->{$action}(...$params);

			if (isset($actionFilters["after"])) {
				$this->applyFilters($controller, $actionFilters["after"], $params);
			}

			return $viewHTML;
		}

		echo "Action <strong>$action</strong> doesn't exists.";
		return;
	}

	/**
	 * Execute the action filters in an specific controller
	 * 
	 * @param App\Controllers\Controller $controller
	 * @param array $filters
	 * @param array $params
	 */
	private function applyFilters($controller, $filters, $params)
	{
		foreach ($filters as $filterName) {
			try {
				$reflector = new \ReflectionObject($controller);
				$filterName = $reflector->getMethod($filterName);
				$filterName->setAccessible(true);
				$filterName->invokeArgs($controller, $params);
			} catch (\Exception $e) {
				throw new \Exception("Action filter: " . $filterName . " is not implemented.");
			}
		}
	}

	private function applyGlobalMiddlewares(Request $request)
	{
		foreach (Middleware::$globals as $middleware) {
			$request = (new $middleware())->execute($request);
			if (!$request) {
				dd($middleware . " Failed");
			}
		}

		return $request;
	}
}
