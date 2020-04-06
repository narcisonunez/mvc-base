<?php

require dirname(dirname(__FILE__)) . "/Core/bootstrap.php";

$router->match(trim($_SERVER["REQUEST_URI"], "/"), $_SERVER['REQUEST_METHOD']);
