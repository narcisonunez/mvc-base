<?php

namespace App\Controllers;

class HomeController extends Controller
{

	private function validateOwner()
	{
		echo "VALIDATED";
	}

	public function index()
	{
		return $this->view('welcome');
	}
}
