<?php
namespace Core;

class Router {

  private $routes = [
    "GET" => [],
    "POST" => [],
    "PATCH" => [],
    "DELETE" => []
  ];

  private $lastRoute;
  private $lastMethod;

  public function resolve() {
    $method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];
    return $this->match(trim($_SERVER["REQUEST_URI"], "/"), );
  }

  public function match($route, $method = 'GET') {
    foreach ($this->routes[$method] as $routeRegex => $routeHandler) {
      $matches = [];

      if (preg_match("/^$routeRegex$/i", $route, $matches)) {
        $params = $this->routeParams($matches);
        
        $controller = "App\\Controllers\\" . $routeHandler['controller'];

        if (!class_exists($controller)) {
          echo "Controller <strong>". $routeHandler['controller'] ."</strong> doesn't exists.";
          return;
        }
        
        $controller = new $controller;
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
    }
    return require VIEWS_PATH . "/errors/404.php";    
  }

  public function get($route, $routeHandler) {
    $this->add('GET', $route, $routeHandler);
    return $this;
  }

  public function post($route, $routeHandler) {
    $this->add('POST', $route, $routeHandler);
    return $this;
  }

  public function path($route, $routeHandler) {
    $this->add('PATH', $route, $routeHandler);
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

  private function applyFilters($controller, $filters, $params)
  {
    foreach($filters as $filterName) {
      try { 
        $reflector = new \ReflectionObject($controller);
        $filterName = $reflector->getMethod($filterName);
        $filterName->setAccessible(true);
        $filterName->invokeArgs($controller, $params);
      } catch(\Exception $e) {
        throw new \Exception("Action filter: " . $filterName . " is not implemented.");
      }
    }
  }

  private function add($method, $route, $routeHandler)
  {
    $regex = $this->parseRouteRegex($route);
    $this->routes[$method][$regex] = $this->getRouteHandler($routeHandler);
    
    $this->lastRoute = $route;
    $this->lastMethod = $method;
  }

  private function getRouteHandler($routeHandler) {
    if (is_string($routeHandler)) {
      $actions = [];
      [$controller, $action] = explode("@", $routeHandler);
      $actions['controller'] = $controller;
      $actions['action'] = $action;
      $routeHandler = $actions;
    }

    return $routeHandler;
  }

  private function parseRouteRegex($route) {
    $route = trim($route, "/");
    $route = preg_replace('/\//', '\\/', $route);
    return preg_replace('/\{([a-z]+)\}/', '(?P<\1>\w+)', $route);
  }

  private function routeParams($matches){
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
