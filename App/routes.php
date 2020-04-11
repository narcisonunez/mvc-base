<?php
// Register all your routes using the $router instance

$router->get('', ['controller' => 'HomeController', 'action' => 'index']);
$router->patch('posts', "HomeController@posts");
