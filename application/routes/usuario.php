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
$base = 'usuario';
$namespace = 'Usuario';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router)  {

    $router->get('/list', 'UserController@list');

    $router->get('/bloqued', 'UserController@bloqued');
   
    $router->post('/auth', 'UserController@auth');
    
    $router->post('/register', 'UserController@register');

    $router->post('/blockade', 'UserController@blockade');

    $router->post('/unlock', 'UserController@unlock');

    $router->get('/emailValidate', 'UserController@emailValidate'); 

    
});

// $router->get($base, function () use ($router) {
//     return response()->json(['response' => 'Hello world by GET']);
// });