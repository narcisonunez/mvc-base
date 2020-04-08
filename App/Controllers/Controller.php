<?php

namespace App\Controllers;

use Core\Request;
use App\Models\Model;

class Controller
{

	protected Model $model;
	protected Request $request;

	/**
	 * Wrapper for the global view helper
	 *
	 * @param string $name View name
	 * @param array $data Data that will be available in the view
	 */
	public function view($name, $data = [])
	{
		return view($name, $data);
	}

	/**
	 * Set or returns the current request
	 *
	 * @param Core\Request $request
	 */
	public function request(Request $request = null)
	{
		if ($request) {
			$this->request = $request;
		}
		return $this->request;
	}
}
