
<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/items', 'Item\ItemController@index');
$router->post('/items', 'Item\ItemController@store');
$router->get('/items/{id}', 'Item\ItemController@show');
$router->put('/items/{id}', 'Item\ItemController@update');
$router->patch('/items/{id}', 'Item\ItemController@update');
$router->delete('/items/{id}', 'Item\ItemController@destroy');
