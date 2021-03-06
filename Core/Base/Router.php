<?php

namespace Core\Base;

use App\Middlewares\Middleware;

class Router
{
	/**
	 * Supported HTTP methods
	 */
	private $routes = [
		"GET" => [],
		"POST" => [],
		"PATCH" => [],
		"DELETE" => []
	];

	private $httpHandler;
	private $lastRoute;
	private $lastMethod;

	public function __construct($httpHandler)
	{
		$this->httpHandler = $httpHandler;
	}

	/**
	 * Match a route and processes the request
	 * 
	 * @param string $route
	 * @param string $method
	 */
	public function resolve($route, $method)
	{
		if ($request = $this->match($route, $method)) {
			$request->method($method);
			return $this->httpHandler->execute($request);
		}
		throw new \Exception("Page not found.", 404);
	}

	/**
	 * Varify if an specific route is registered for an specific method
	 * 
	 * @param string $route
	 * @param string $method
	 * 
	 * @return Core\Request | null
	 */
	public function match($route, $method = 'GET')
	{
		foreach ($this->routes[$method] as $routeRegex => $routeHandler) {
			$matches = [];
			if (preg_match("/^$routeRegex$/i", $route, $matches)) {
				return new Request($routeHandler, $this->routeParams($matches));
			}
		}
		return null;
	}

	/**
	 * Register a route to the get method
	 * 
	 * @param string $route 
	 * @param array|string $routeHandler
	 * 
	 * @return this
	 */
	public function get($route, $routeHandler)
	{
		$this->add('GET', $route, $routeHandler);
		return $this;
	}

	/**
	 * Register a route to the post method
	 * 
	 * @param string $route 
	 * @param array|string $routeHandler
	 * 
	 * @return this
	 */
	public function post($route, $routeHandler)
	{
		$this->add('POST', $route, $routeHandler);
		return $this;
	}

	/**
	 * Register a route to the patch method
	 * 
	 * @param string $route 
	 * @param array|string $routeHandler
	 * 
	 * @return this
	 */
	public function patch($route, $routeHandler)
	{
		$this->add('PATCH', $route, $routeHandler);
		return $this;
	}

	/**
	 * Register a route to the delete method
	 * 
	 * @param string $route 
	 * @param array|string $routeHandler
	 * 
	 * @return this
	 */
	public function delete($route, $routeHandler)
	{
		$this->add('DELETE', $route, $routeHandler);
		return $this;
	}

	/**
	 * Add a new route to the routes array
	 * 
	 * @param string $method
	 * @param string $route
	 * @param array $routeHandler
	 */
	private function add($method, $route, $routeHandler)
	{
		$regex = $this->parseRouteRegex($route);
		$this->routes[$method][$regex] = $this->getRouteHandler($routeHandler);

		$this->lastRoute = $regex;
		$this->lastMethod = $method;
	}

	/**
	 * Register action filters for the last route
	 * 
	 * @param array $filters
	 */
	public function actionFilters($filters)
	{
		if (key_exists("before", $filters) || key_exists("after", $filters)) {
			$this->routes[$this->lastMethod][$this->lastRoute]["actionFilters"] = $filters;
			return $this;
		}
		throw new \Exception('Invalid action filters. before or after', 500);
	}

	/**
	 * Register middlewares for the last route
	 * 
	 * @param array $middlewares
	 */
	public function middlewares($middlewares)
	{
		if (!is_array($middlewares)) {
			throw new \Exception('Middleware must be an array.', 500);
		}
		foreach ($middlewares as $middleware) {
			if (!key_exists($middleware, Middleware::$routes)) {
				throw new \Exception('Middleware not found in the routes middleware array.', 500);
			}
			$this->routes[$this->lastMethod][$this->lastRoute]["middlewares"][] = Middleware::$routes[$middleware];
		}
		return $this;
	}

	/**
	 * Get the route handlers. If the handler is a string, split it to array
	 * 
	 * @param string|array $routeHandler
	 * @return array
	 */
	private function getRouteHandler($routeHandler)
	{
		if (is_string($routeHandler)) {
			$actions = [];
			[$controller, $action] = explode("@", $routeHandler);
			$actions['controller'] = $controller;
			$actions['action'] = $action;
			$routeHandler = $actions;
		}

		return $routeHandler;
	}

	/**
	 * Parse route string to a route regex
	 * 
	 * @param string $route
	 * @return string
	 */
	private function parseRouteRegex($route)
	{
		$route = trim($route, "/");
		$route = preg_replace('/\//', '\\/', $route);
		return preg_replace('/\{([a-z]+)\}/', '(?P<\1>\w+)', $route);
	}

	/**
	 * Get the placeholder values in a route
	 * 
	 * @param array $matches
	 * @return array
	 */
	private function routeParams($matches)
	{
		$params = array_values(array_filter($matches, function ($match, $key) {
			if (is_string($key)) {
				return true;
			}
		}, ARRAY_FILTER_USE_BOTH));

		$params = array_map(function ($param) {
			if (is_numeric($param)) return intval($param);
			return $param;
		}, $params);

		return $params;
	}
}
