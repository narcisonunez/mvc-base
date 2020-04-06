<?php
// Register all your routes using the $router instance
$router->get('', 'HomeController@index');
$router->get('login', 'HomeController@login');

$router->post('posts', 'HomeController@store');