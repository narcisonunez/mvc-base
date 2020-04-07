<?php

require dirname(dirname(__FILE__)) . "/core/bootstrap.php";

$method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];

$uri = explode('?', $_SERVER["REQUEST_URI"]);
$uri = array_shift($uri);

$router->resolve(trim($uri, "/"), $method);
