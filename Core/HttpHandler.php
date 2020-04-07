<?php
namespace Core;

class HttpHandler {
  public function execute(Request $request)
  {
    $params = $request->routeParams;
    $routeHandler = $request->routeHandler;

    $controller = "App\\Controllers\\" . $routeHandler['controller'];

    if (!class_exists($controller)) {
      echo "Controller <strong>". $routeHandler['controller'] ."</strong> doesn't exists.";
      return;
    }
    
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
}