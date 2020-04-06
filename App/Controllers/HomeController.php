<?php
namespace App\Controller;

class HomeController {
  public function index()
  {
    return require dirname(dirname(__FILE__)) . "/Views/welcome.php";
  }

  public function store()
  {
    die("THIS WAS A POST REQUEST");
  }
}