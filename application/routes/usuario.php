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

// Entry point da API
$base = '/usuario';

$router->group(['prefix' => $base], function () use ($router) {

    $router->get('', 'UserController@obterUsuario');

    $router->get('{id}', 'UserController@obterUsuario');

    $router->post('/auth', 'UserController@auth');
    
    //$router->get('{name}/not', 'UserController@notObterUsuario');

    $router->post('/register', 'UserController@register');
});

// $router->get($base, function () use ($router) {
//     return response()->json(['response' => 'Hello world by GET']);
// });