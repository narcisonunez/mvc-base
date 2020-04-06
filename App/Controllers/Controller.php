<?php
namespace App\Controllers;

class Controller {
  
  public function view($name, $data = [])
  {
    $name = str_replace(".", "/", $name);
    
    $view = dirname(dirname(__FILE__)) . "/Views/$name.php";

    if (!file_exists($view)) {
      echo 'View doesn\'t exists.';
      return;
    }
    
    extract($data);
    unset($data);
    return require $view;
  }
}