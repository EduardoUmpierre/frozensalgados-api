<?php

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

Dusterio\LumenPassport\LumenPassport::routes($router);

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1', 'middleware' => []], function () use ($router) {
    $router->group(['prefix' => 'customers'], function () use ($router) {
        $router->get('/', ['uses' => 'CustomerController@getAll']);
        $router->post('/', ['uses' => 'CustomerController@create']);
    });

    $router->get('/orders', function () {
        return \App\Order::with(['customer', 'orderProduct', 'orderProduct.product'])->get();
    });

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/', ['uses' => 'ProductController@getAll']);
        $router->post('/', ['uses' => 'ProductController@create']);
    });
});