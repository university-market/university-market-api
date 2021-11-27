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
$base = 'charts';
$namespace = 'Charts';

$router->group(['prefix' => $base, 'namespace' => $namespace], function () use ($router) {

    /**
     * @description Data Source para gráficos de denúncias
     * @route host/charts/denuncia[/...]
     */
    $router->group(['prefix' => 'denuncia', 'namespace' => 'Denuncia'], function() use ($router) {

        // Trazer dados quantitativos de denuncias salvas no sistema
        $router->get('quantidade', 'DenunciaChartsController@quantificar');

    });

});