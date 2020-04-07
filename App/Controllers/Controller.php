<?php
namespace App\Controllers;
use Core\Request;

class Controller {
  private Request $request;

  public function view($name, $data = [])
  {
    return view($name, $data);
  }

  public function request(Request $request = null)
  {
    if ($request) {
      $this->request = $request;  
    }
    return $this->request;
  }

}