<?php

namespace Core\Base;

class Database
{
	static private $conn = null;

	public static function getInstance($host, $dbName, $user, $pass)
	{
		if (static::$conn == null) {
			try {
				$driver = config("database.driver", "");
				$fetchMode = config("database.fetch_mode");
				$conn = new \PDO("$driver:host=$host;dbname=$dbName", $user, $pass);
				$conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);
				static::$conn = $conn;
			} catch (\PDOException $e) {
				throw new \Exception($e->getMessage(), 500, $e);
			}
		}
		return static::$conn;
	}
}
