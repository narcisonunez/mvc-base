<?php

function config($name, $default = null) 
{
  $config = require APP_PATH . "/config.php";
  if (key_exists($name, $config)) {
    return $config[$name];
  }
  return $default; 
}

function view($name, $data = [])
{
  $name = str_replace(".", "/", $name);
  $view = VIEWS_PATH . "/$name.php";

  if (!file_exists($view)) {
    echo 'View doesn\'t exists.';
    return;
  }

  extract($data);
  unset($data);
  return require $view;
}

function env_value($key, $default = null)
{
  global $globalEnvironmentValues;
  
  if (key_exists($key, $globalEnvironmentValues)) {
    return $globalEnvironmentValues[$key];
  }

  return $default;
}

function redirect($path)
{
  if (!headers_sent()) {
    return header('Location: ' . $path);
  }
}

function dd(...$data)
{
  if (is_array($data[0])) {
    $data = $data[0];
  }

  foreach($data as $key => $info) {
    if (is_string($key)) {
      echo "<h3>$key</h3>\n";
    }
    var_dump($info);
    echo "\n\n";
  }
  
  die();
}

function form_method($method)
{
  echo '<input type="hidden" name="_method" value="'. $method .'">';
}