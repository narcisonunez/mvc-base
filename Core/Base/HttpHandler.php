<?php

namespace Core\Base;

use App\Middlewares\Middleware;
use Core\Base\Request;
use Core\Middlewares\MiddlewareContract;

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
		$action = $routeHandler['action'];
		$actionFilters = isset($routeHandler['actionFilters']) ? $routeHandler['actionFilters'] : [];
		$routeMiddlewares = isset($routeHandler['middlewares']) ? $routeHandler['middlewares'] : [];

		$controller = "App\\Controllers\\" . $routeHandler['controller'];

		if (!class_exists($controller)) {
			echo "Controller <strong>" . $routeHandler['controller'] . "</strong> doesn't exists.";
			return;
		}

		$request = $this->applyGlobalMiddlewares($request);
		$request = $this->applyRouteMiddlewares($routeMiddlewares, $request);

		$controller = new $controller;
		$controller->request($request);



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

	/**
	 * Execute all the route middlewares
	 * 
	 * @param Request $request
	 * @return Request
	 */
	private function applyRouteMiddlewares($middlewares, Request $request)
	{
		return $this->applyMiddlwares($middlewares, $request);
	}

	/**
	 * Execute all the global middlewares
	 * 
	 * @param Request $request
	 * @return Request
	 */
	private function applyGlobalMiddlewares(Request $request)
	{
		return $this->applyMiddlwares(Middleware::$globals, $request);
	}

	/**
	 * Execute the middleware
	 * 
	 * @param array $middlewares
	 * @param Request $request
	 * 
	 * @return Request
	 */
	private function applyMiddlwares($middlewares, $request)
	{
		foreach ($middlewares as $middleware) {
			$instance = new $middleware();

			if (!$instance instanceof MiddlewareContract) {
				echo $middleware . " must implement Core\\Middlewares\\MiddlewareContract";
				die();
			}

			$request = $instance->execute($request);

			if (!$request) {
				dd($middleware . " Failed"); // TODO: redirect to the back page
			}
		}

		return $request;
	}
}
