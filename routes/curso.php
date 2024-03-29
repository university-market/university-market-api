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
$base = 'curso';
$namespace = 'Curso';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router) {

  $router->get('all', 'CursoController@listarTodos');

  $router->get('{instituicaoId}/listar', 'CursoController@listarPorInstituicao');
});