<?php

require dirname(dirname(__FILE__)) . "/core/bootstrap.php";

$router->match(trim($_SERVER["REQUEST_URI"], "/"), $_SERVER['REQUEST_METHOD']);
