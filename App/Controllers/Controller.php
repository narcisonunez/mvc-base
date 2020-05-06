<?php

namespace App\Controllers;

use Core\Base\Request;
use App\Models\Model;

class Controller
{
	protected $model;
	protected $request;

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
