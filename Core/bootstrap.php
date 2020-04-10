<?php
session_start();

define("ROOT_PATH", dirname(dirname(__FILE__)));
define("APP_PATH", dirname(dirname(__FILE__)) . "/app");
define("CORE_PATH", dirname(dirname(__FILE__)) . "/core");
define("CONTROLLERS_PATH", dirname(dirname(__FILE__)) . "/app/Controllers");
define("MODELS_PATH", dirname(dirname(__FILE__)) . "/app/Models");
define("VIEWS_PATH", dirname(dirname(__FILE__)) . "/app/Views");

spl_autoload_register(function ($class) {
	require ROOT_PATH . "/" . str_replace('\\', "/", $class) . ".php";
});

set_error_handler("Core\Base\ExceptionHandler::errorHandler");
set_exception_handler("Core\Base\ExceptionHandler::exceptionHandler");
if (!file_exists(ROOT_PATH . '/logs/server.error.log')) {
	mkdir(ROOT_PATH . '/logs');
}
ini_set('error_log', ROOT_PATH . '/logs/server.error.log');

/**
 * Register all the keys in the .env file as global to be accesible
 */
$GLOBALS["globalEnvironmentValues"] = [];
$fhandler = fopen(ROOT_PATH . "/.env", 'r') or die("Missing .env file");
while (!feof($fhandler)) {
	$configuration = fgets($fhandler);
	$configuration = explode("=", $configuration);
	if (strlen($configuration[0]) == 1) {
		continue;
	}
	$globalEnvironmentValues[trim($configuration[0])] = str_replace("\"", "", trim($configuration[1]));
}
fclose($fhandler);
unset($fhandler);

require ROOT_PATH . '/vendor/autoload.php';

/**
 * Configure Twig Engine
 */
if (config('template_engine') == "twig") {
	$loader = new \Twig\Loader\FilesystemLoader(VIEWS_PATH);
	$twig = new \Twig\Environment($loader);
	$twig->addExtension(new Core\Base\TwigFunctionsExtension());
	$GLOBALS["twig"] = $twig;
}

$router = new Core\Base\Router(new Core\Base\HttpHandler());
require APP_PATH . "/routes.php";
