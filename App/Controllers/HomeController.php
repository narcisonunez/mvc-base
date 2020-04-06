<?php
namespace App\Controllers;

class HomeController extends Controller {
  
  private $message;

  public function index()
  {
    return $this->view('welcome');
  }
}