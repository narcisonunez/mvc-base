<?php

require dirname(dirname(__FILE__)) . "/core/bootstrap.php";

$method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];
$router->resolve(trim($_SERVER["REQUEST_URI"], "/"), $method);
