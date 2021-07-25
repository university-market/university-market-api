<?php

/** @var \Laravel\Lumen\Routing\Router $router */



// Entry point da API
$base = '/sale';

$router->group(['prefix' => $base], function () use ($router) {

    $router->get('list[/{id}]', 'SaleController@listByCourseId');

});
