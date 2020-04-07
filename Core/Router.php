<?php
namespace Core;

class Router {

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

  public function resolve($route, $method) {
    if ($request = $this->match($route, $method)) {
      return $this->httpHandler->execute($request);
    }
    return require VIEWS_PATH . "/errors/404.php"; 
  }

  public function match($route, $method = 'GET') {
    foreach ($this->routes[$method] as $routeRegex => $routeHandler) {
      $matches = [];
      if (preg_match("/^$routeRegex$/i", $route, $matches)) {
        return new Request($routeHandler, $this->routeParams($matches));
      }
    }
  }

  public function get($route, $routeHandler) {
    $this->add('GET', $route, $routeHandler);
    return $this;
  }

  public function post($route, $routeHandler) {
    $this->add('POST', $route, $routeHandler);
    return $this;
  }

  public function patch($route, $routeHandler) {
    $this->add('PATCH', $route, $routeHandler);
    return $this;
  }

  public function delete($route, $routeHandler) {
    $this->add('DELETE', $route, $routeHandler);
    return $this;
  }

  public function withActionFilters($filters)
  {
    if (!key_exists("before", $filters) || !key_exists("after", $filters)) {
      throw new \Exception('Invalid action filters. before or after');
    }
    
    $this->routes[$this->lastMethod][$this->lastRoute]["actionFilters"] = $filters;
  }

  private function add($method, $route, $routeHandler)
  {
    $regex = $this->parseRouteRegex($route);
    $this->routes[$method][$regex] = $this->getRouteHandler($routeHandler);
    
    $this->lastRoute = $route;
    $this->lastMethod = $method;
  }

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

  private function parseRouteRegex($route) 
  {
    $route = trim($route, "/");
    $route = preg_replace('/\//', '\\/', $route);
    return preg_replace('/\{([a-z]+)\}/', '(?P<\1>\w+)', $route);
  }

  private function routeParams($matches)
  {
    $params = array_values(array_filter($matches, function($match, $key){
        if (is_string($key)) {
            return true;
        }
      }, ARRAY_FILTER_USE_BOTH));

    $params = array_map(function($param){
      if (is_numeric($param)) return intval($param);
      return $param;
    }, $params);
    
    return $params;
  }
}
