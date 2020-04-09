<?php

namespace App\Controllers;

class HomeController extends Controller
{
	private function index()
	{
		return $this->view('welcome');
	}
}
