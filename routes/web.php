<?php


$router->post('auth/login', ['uses' => 'AuthController@authenticate']);

// ** prefix api/v1/ todos by each auth::user()
$router->group(['prefix' => 'api/v1', 'middleware' => 'jwt.auth'], function() use ($router){
	$router->get('todos', 'TodoController@index');

	$router->get('todos/{filter}', 'TodoController@showBy');
	

	$router->post('todo', 'TodoController@store');
	$router->post('todo/{id}', 'TodoController@setComplete');
	$router->delete('todo/{id}', 'TodoController@destroy');
});



$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function () use ($router) {
	return str_random(32);
});
