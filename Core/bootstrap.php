<?php
define("ROOT_PATH", dirname(dirname(__FILE__)));
define("APP_PATH", dirname(dirname(__FILE__)) . "/app");
define("CORE_PATH", dirname(dirname(__FILE__)) . "/core");
define("CONTROLLERS_PATH", dirname(dirname(__FILE__)) . "/app/Controllers");
define("MODELS_PATH", dirname(dirname(__FILE__)) . "/app/Models");
define("VIEWS_PATH", dirname(dirname(__FILE__)) . "/app/Views");

$GLOBALS["globalEnvironmentValues"] = [];
$fhandler = fopen(ROOT_PATH . "/.env", 'r') or die("Missing .env file");
while(!feof($fhandler)) {
  [$key, $value] = explode("=", fgets($fhandler));
  $globalEnvironmentValues[trim($key)] = str_replace("\"", "", trim($value));
}
fclose($fhandler);
unset($fhandler);

require ROOT_PATH . '/vendor/autoload.php';
require CORE_PATH . "/Router.php";
$router = new Core\Router;

require ROOT_PATH . "/resources/routes/web.php";