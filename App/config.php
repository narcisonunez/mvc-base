<?php

/**
 * Config file to save all the configuration keys the app will be using
 * Ex. EMAIL credentials
 */

return [

	"app_name" => env_value("APP_NAME", "MVC App"),

	"database" => [
		"driver" => "mysql", // mysql | pgsql
		"fetch_mode" => \PDO::FETCH_OBJ
	],
];
