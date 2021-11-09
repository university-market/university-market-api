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
$base = 'estudante';
$namespace = 'Estudante';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router) {

  // Obter estudante
  $router->get('{estudanteId}', 'EstudanteController@obter');

  // Criar estudante
  $router->post('', 'EstudanteController@criar');

  // bloquear estudante
  $router->post('bloquear', 'EstudanteController@bloquear');

  //obter dados do perfil estudante
  $router->get('dados/{estudanteId}','EstudanteController@obterDados');

  //cadastrar contatos do estudante
  $router->post('contatos','EstudanteController@cadastrarContato');

  //deleta  contato do estudante
  $router->delete('contatos/{contatoId}','EstudanteController@deletarContato');

  //obter contatos do estudante
  $router->get('contatos/{estudanteId}','EstudanteController@obterContatos');

  //editat contatos do estudante
  $router->put('contatos','EstudanteController@editarContato');

  //obter endereços pelo estudande id
  $router->get('endereco/{estudanteId}','EstudanteController@obterEndereco');

  //cadastrar endereços 
  $router->get('endereco','EstudanteController@cadastrarEndereco');

});