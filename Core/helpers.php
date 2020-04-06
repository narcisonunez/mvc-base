<?php

function config($name, $default = null) 
{
  $config = require ROOT_PATH . "/App/config.php";
  if (key_exists($name, $config)) {
    return $config[$name];
  }
  return $default; 
}

function view($name, $data = [])
{
  $name = str_replace(".", "/", $name);
  $view = ROOT_PATH . "/App/Views/$name.php";

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

function dd($data)
{
  die(var_dump($data));
}