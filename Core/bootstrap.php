<?php
define("ROOT_PATH", dirname(dirname(__FILE__)));
define("APP_PATH", dirname(dirname(__FILE__)) . "/app");
define("CORE_PATH", dirname(dirname(__FILE__)) . "/core");
define("CONTROLLERS_PATH", dirname(dirname(__FILE__)) . "/app/Controllers");
define("MODELS_PATH", dirname(dirname(__FILE__)) . "/app/Models");
define("VIEWS_PATH", dirname(dirname(__FILE__)) . "/app/Views");

spl_autoload_register(function ($class) {
	require ROOT_PATH . "/" . str_replace('\\', "/", $class) . ".php";
});

/**
 * Register all the keys in the .env file as global to be accesible
 */
$GLOBALS["globalEnvironmentValues"] = [];
$fhandler = fopen(ROOT_PATH . "/.env", 'r') or die("Missing .env file");
while (!feof($fhandler)) {
	[$key, $value] = explode("=", fgets($fhandler));
	$globalEnvironmentValues[trim($key)] = str_replace("\"", "", trim($value));
}
fclose($fhandler);
unset($fhandler);

require ROOT_PATH . '/vendor/autoload.php';
$router = new Core\Base\Router(new Core\Base\HttpHandler());

require ROOT_PATH . "/resources/routes/web.php";
