<?php
define("ROOT_PATH", dirname(dirname(__FILE__)));

require ROOT_PATH . '/vendor/autoload.php';

require ROOT_PATH . "/Core/Router.php";
$router = new Core\Router;

require ROOT_PATH . "/resources/routes/web.php";