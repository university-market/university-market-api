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
$base = 'publicacao';
$namespace = 'Publicacao';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router) {

    // Alterar publicacao
    $router->put('{publicacaoId}', 'PublicacaoController@alterar');

    // Criar nova publicacao
    $router->post('create', 'PublicacaoController@criar');

    // Listar publicacoes
    $router->get('listar', 'PublicacaoController@listar');

    // Obter publicacao
    $router->get('{publicacaoId}', 'PublicacaoController@obter');
});