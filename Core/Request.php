<?php

namespace Core;

class Request {
  public $routeHandler;
  public $routeParams;
  public $get = [];
  public $post = [];

  public function __construct($routeHandler, $params)
  {
    $this->routeHandler = $routeHandler;
    $this->routeParams = $params;
    $this->get = $_GET;
    $this->post = $_POST;
  }

  public function uri()
  {
    return $_SERVER['REQUEST_URI'];
  }

  public function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public function get($key, $default = null)
  {
    return isset($_GET[$key]) ? $_GET[$key] : $default;
  }

  public function post($key, $default = null)
  {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
  }

}