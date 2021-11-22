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

    // Upload de imagem da publicacao
    $router->post('{publicacaoId}/image', 'PublicacaoController@uploadImagemPublicacao');

    // Criar nova publicacao
    $router->post('create', 'PublicacaoController@criar');

    // Listar publicacoes
    $router->get('listar', 'PublicacaoController@listar');

    // Listar publicacoes por id de curso
    $router->get('listar/{cursoId}/curso', 'PublicacaoController@listarByCurso');
    
    // Obter publicacao
    $router->get('{publicacaoId}', 'PublicacaoController@obter');

    // Obter tags da publicacao
    $router->get('{publicacaoId}/tags', 'PublicacaoController@obterTags');

    // Excluir publicacao
    $router->delete('{publicacaoId}', 'PublicacaoController@excluir');

    // Oberter Publicação por id de estudante
    $router->get('/estudante/{estudanteId}', 'PublicacaoController@obterByUser');

    // Listar publicacoes
    $router->post('denunciar', 'PublicacaoController@denunciar');
    // Marcar publicacao como vendida
    $router->post('marcarVendida', 'PublicacaoController@marcarPublicacaoComoVendida');
    
});