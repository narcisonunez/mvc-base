<?php
define("ROOT_PATH", dirname(dirname(__FILE__)));

$GLOBALS["globalEnvironmentValues"] = [];
$fhandler = fopen(ROOT_PATH . "/.env", 'r') or die("Missing .env file");
while(!feof($fhandler)) {
  [$key, $value] = explode("=", fgets($fhandler));
  $globalEnvironmentValues[trim($key)] = str_replace("\"", "", trim($value));
}
fclose($fhandler);
unset($fhandler);

require ROOT_PATH . '/vendor/autoload.php';
require ROOT_PATH . "/Core/Router.php";
$router = new Core\Router;

require ROOT_PATH . "/resources/routes/web.php";