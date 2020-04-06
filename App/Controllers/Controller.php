<?php
namespace App\Controllers;

class Controller {
  
  public function view($name, $data = [])
  {
    return view($name, $data);
  }
}