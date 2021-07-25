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
$base = 'recuperacao';
$namespace = 'Forgotsenha';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router)  {

    $router->post('/restaurar', 'ForgotPasswordController@checksenha');

    $router->post('/email', 'ForgotPasswordController@forgot'); 

    
    
});

// $router->get($base, function () use ($router) {
//     return response()->json(['response' => 'Hello world by GET']);
// });