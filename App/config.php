<?php

/**
 * Config file to save all the configuration keys the app will be using
 * Ex. EMAIL credentials
 */

return [

	"app_name" => env_value("APP_NAME", "MVC App"),

	"template_engine" => "twig", // none | twig

	/**
	 * Configurations related to the database connection
	 * The supported drivers are listed in the PDO documentation
	 */
	"database" => [
		"driver" => "mysql", // mysql | pgsql
		"fetch_mode" => \PDO::FETCH_OBJ
	],
];
