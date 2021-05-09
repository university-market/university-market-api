<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Entry point da aplicação
$base = '/';

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

$router->get($base, function () use ($router) {
    return response()->json(['response' => 'Hello world by GET']);
});
