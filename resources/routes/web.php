<?php
// Register all your routes using the $router instance
$router->get('', 'HomeController@index');
$router->post('posts', 'HomeController@store');