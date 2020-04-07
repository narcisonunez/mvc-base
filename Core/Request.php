<?php

namespace Core;

class Request {
  /**
   * Contains the handlers - Controller, Action, Action Filters, etc.
   * @var array $routeParams
   */
  public $routeHandler;

  /**
   * Contains all the placeholder values in the url
   * @var array $routeParams
   */
  public $routeParams;

  /**
   * Get parameters in the url
   * @var array $get
   */
  public $get = [];

  /**
   * Post parameters in the request
   * @var array $post
   */
  public $post = [];

  public function __construct($routeHandler, $params)
  {
    $this->routeHandler = $routeHandler;
    $this->routeParams = $params;
    $this->get = $_GET;
    $this->post = $_POST;
  }

  /**
   * Request URI
   * 
   * @return string
   */
  public function uri()
  {
    return $_SERVER['REQUEST_URI'];
  }
  
  /**
   * Request Method
   * 
   * @return string
   */
  public function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  /**
   * Returns a value from the $_GET superglobal
   * 
   * @param string $key
   * @param mixed $default
   * 
   * @return string|mixed
   */
  public function get($key, $default = null)
  {
    return $this->getValues($_GET, $key, $default);
  }

  /**
   * Returns a value from the $_POST superglobal
   * 
   * @param string $key
   * @param mixed $default
   * 
   * @return string|mixed
   */
  public function post($key, $default = null)
  {
    return $this->getValues($_POST, $key, $default);
  }

  /**
   * Get values from a source based on the key or return the default
   */
  private function getValues($source, $key, $default)
  {
    if (is_string($key)) {
      return isset($source[$key]) ? $source[$key] : $default;
    }

    $values = [];
    foreach ($key as $keyName) {
      $values[] = isset($source[$keyName]) ? $source[$keyName] : $default;
    }
    return $values;
  }

}