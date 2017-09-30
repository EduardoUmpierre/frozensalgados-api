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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('/customers', function () {
        return App\Customer::all();
    });

    $router->get('/orders', function () {
        return \App\Order::with(['customer', 'orderProduct', 'orderProduct.product'])->get();
    });

    $router->get('/products', function () {
        return \App\Product::all();
    });
});