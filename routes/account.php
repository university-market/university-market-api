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
$base = 'account';
$namespace = 'Account';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router) {

    // Obter profile
    $router->get('profile', 'AccountController@profile');

    // Solicitar recuperação de senha
    $router->post('recuperacaosenha/solicitar', 'AccountController@solicitarRecuperacaoSenha');

    // Validar token de recuperacao de senha
    $router->get('recuperacaosenha/validar/token/{token}', 'AccountController@validarTokenRecuperacaoSenha');

    // Validar email de recuperacao de senha
    $router->get('recuperacaosenha/validar/email', 'AccountController@validarEmailRecuperacaoSenha');

    // Alterar senha
    $router->put('recuperacaosenha/alterar', 'AccountController@alterarSenha');

});