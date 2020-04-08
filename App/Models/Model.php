<?php

namespace App\Models;

use Core\Database;

abstract class Model
{
	protected \PDO $db;

	public function __construct()
	{
		$this->db = Database::getInstance(
			env_value("DB_HOST"),
			env_value("DB_NAME"),
			env_value("DB_USER"),
			env_value("DB_PASSWORD")
		);
	}
}
