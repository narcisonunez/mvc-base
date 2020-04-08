<?php

namespace Core\Base;

class Database
{
	static private ?\PDO $conn = null;

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
				dd($e->getMessage());
			}
		}
		return static::$conn;
	}
}
