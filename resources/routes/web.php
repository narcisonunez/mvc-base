<?php
// Register all your routes using the $router instance

$router->get('', 'HomeController@index');

// $router->get('posts/{id}/edit', 'HomeController@index')
//       ->withActionFilters([
//         "before" => ['validateBefore'],
//         "after" => ['validateAfter']
//       ]);

// $router->get('/home', ['controller' => 'HomeController', 'action' => 'index']);

// $router->post('posts', 'HomeController@store');