<?php

namespace Core;

class Database
{
	protected \PDO $conn;

	public function __construct($host, $dbName, $user, $pass)
	{
		try {
			$driver = config("database.driver", "");
			$fetchMode = config("database.fetch_mode");

			$this->conn = new \PDO("$driver:host=$host;dbname=$dbName", $user, $pass);
			$this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);
		} catch (\PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getInstance()
	{
		return $this->conn;
	}
}
