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
$base = 'instituicao';
$namespace = 'Instituicao';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router) {

  // Listar todas as instituicoes
  $router->get('buscar', 'InstituicaoController@listarTodas');

  // Listar instituicoes disponiveis para cadastro de estudantes
  $router->get('buscar/disponiveis', 'InstituicaoController@listarDisponiveis');

  // Cadastrar instituicao
  $router->post('', 'InstituicaoController@cadastrar');

  // Aprovar cadastro de uma instituicao
  $router->post('{instituicaoId}/aprovar', 'InstituicaoController@aprovar');

  // Ativar cadastro da instituicao
  $router->put('{instituicaoId}/ativar', 'InstituicaoController@ativar');

  // Desativar cadastro da instituicao
  $router->put('{instituicaoId}/desativar', 'InstituicaoController@desativar');
});