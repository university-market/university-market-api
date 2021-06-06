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
$base = '/publicacao';

$router->group(['prefix' => $base], function () use ($router) {

    $namespace = 'Publicacao';

    // Criar nova publicacao
    $router->post('/', "$namespace\PublicacaoController@criar");
});