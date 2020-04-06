<?php

require dirname(dirname(__FILE__)) . "/Core/Router.php";
$router = new Core\Router;

require dirname(dirname(__FILE__)) . "/resources/routes/web.php";
require dirname(dirname(__FILE__)) . "/resources/routes/api.php";