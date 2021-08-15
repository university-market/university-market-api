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
$base = 'auth';
$namespace = 'Auth';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router) {

    // Efetuar login
    $router->post('estudante/login', 'AuthController@loginEstudante');

});