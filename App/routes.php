<?php
// Register all your routes using the $router instance

$router->get('', ['controller' => 'HomeController', 'action' => 'index']);
$router->get('posts/{id}/edit', "HomeController@posts");

$router->patch('posts', "HomeController@posts");
