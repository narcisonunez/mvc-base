<?php
namespace Core;

class Router {
  protected $routes = [
    "GET" => [],
    "POST" => [],
    "PATCH" => [],
    "DELETE" => []
  ];

  public function resolve() {
    return $this->match(trim($_SERVER["REQUEST_URI"], "/"), $_SERVER['REQUEST_METHOD']);
  }

  public function match($route, $method = 'GET') {
    $controllerDir = CONTROLLERS_PATH . "/";
    $errorsDir = VIEWS_PATH . "/errors/";

    foreach ($this->routes[$method] as $routeRegex => $controllerAction) {
      $matches = [];

      if (preg_match("/^$routeRegex$/i", $route, $matches)) {  
        $params = $this->routeParams($matches);

        $controller = $controllerDir . $controllerAction['controller'] . ".php";
    
        if (!file_exists($controller)) {
          echo "Controller <strong>". $controllerAction['controller'] ."</strong> doesn't exists.";
          return;
        }
    
        require $controller;
        $controller = "App\\Controllers\\" . $controllerAction['controller'];
        
        $controller = new $controller;
        $action = $controllerAction['action'];
    
        if (method_exists($controller, $action)) {
          return $controller->{$action}(...$params);
        }
    
        echo "Action <strong>$action</strong> doesn't exists.";
        return;
      }
    }
    
    return require $errorsDir . "404.php";    
  }

  public function get($route, $controllerAction) {
    $regex = $this->parseRouteRegex($route);
    $this->routes['GET'][$regex] = $this->getControllerAction($controllerAction);
  }

  public function post($route, $controllerAction) {
    $regex = $this->parseRouteRegex($route);
    $this->routes['POST'][$regex] = $this->getControllerAction($controllerAction);
  }

  public function path($route, $controllerAction) {
    $regex = $this->parseRouteRegex($route);
    $this->routes['PATCH'][$regex] = $this->getControllerAction($controllerAction);
  }

  public function delete($route, $controllerAction) {
    $regex = $this->parseRouteRegex($route);
    $this->routes['DELETE'][$regex] = $this->getControllerAction($controllerAction);
  }

  private function getControllerAction($controllerAction) {
    if (is_string($controllerAction)) {
      $actions = [];
      [$controller, $action] = explode("@", $controllerAction);
      $actions['controller'] = $controller;
      $actions['action'] = $action;
      $controllerAction = $actions;
    }

    return $controllerAction;
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
      if (is_numeric($param)) {
        return intval($param);
      }

      return $param;
    }, $params);
    return 0;
  }

}