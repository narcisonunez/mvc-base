<?php

namespace App\Models;

use Core\Database;

abstract class Model
{
	protected \PDO $db;

	public function __construct()
	{
		$db = new Database(
			env_value("DB_HOST"),
			env_value("DB_NAME"),
			env_value("DB_USER"),
			env_value("DB_PASSWORD")
		);

		$this->db = $db->getInstance();
	}
}
