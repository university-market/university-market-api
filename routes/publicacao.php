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
    $router->get('curso', 'PublicacaoController@listarByCurso');
    
    // Obter publicacao
    $router->get('{publicacaoId}', 'PublicacaoController@obter');

    // Obter tags da publicacao
    $router->get('{publicacaoId}/tags', 'PublicacaoController@obterTags');

    // Excluir publicacao
    $router->delete('{publicacaoId}', 'PublicacaoController@excluir');

    // Obter Publicação por id de estudante
    $router->get('/estudante/{estudanteId}', 'PublicacaoController@obterByUser');

    // denunciar publicacoes
    $router->post('denunciar', 'PublicacaoController@denunciar');

    // Marcar publicacao como vendida
    $router->post('marcarVendida', 'PublicacaoController@marcarPublicacaoComoVendida');

    // Obter Publicação por id de estudante
    $router->get('obter/tiposDenuncias', 'PublicacaoController@obterTiposDenuncias');

     // Buscar publicação 
     $router->get('buscar/pesquisarPublicacoes', 'PublicacaoController@pesquisarPublicacoes');

     // Buscar por curso
     $router->get('buscar/curso/publicacoes/{cursoId}', 'PublicacaoController@pesquisarPublicacoesByCursos');

     // Favoritar Publicacao
     $router->post('favoritar/publicacao', 'PublicacaoController@favoritarPublicacao');

     // Obter Publicações favoritas por id de estudante
    $router->get('/estudante/favoritas/{estudanteId}', 'PublicacaoController@obterFavoritasByUser');

    // Excluir publicacao
    $router->delete('/excluir/favorita/{publicacaoId}', 'PublicacaoController@excluirFavorita');

});