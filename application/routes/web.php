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
$base = '/';

$router->group(['prefix' => $base], function () use ($router) {

    $router->get('course/grid', 'CourseController@obterCourse');

    $router->get('{id}', 'Controller@obterUsuario');
    
    $router->get('{name}/not', 'Controller@notObterUsuario');

    // $router->post('{name}', 'Controller@criarUsuario');

});

// $router->get($base, function () use ($router) {
//     return response()->json(['response' => 'Hello world by GET']);
// });