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

/**
 * Api
 */
$router->group(['prefix' => 'api/v1', 'middleware' => ['auth']], function () use ($router) {
    /**
     * Customers
     */
    $router->group(['prefix' => 'customers'], function () use ($router) {
        $router->get('/', 'CustomerController@getAll');
        $router->get('/{id}', 'CustomerController@getOne');
        $router->post('/', 'CustomerController@create');
        $router->put('/{id}', 'CustomerController@update');
        $router->delete('/{id}', 'CustomerController@delete');
    });

    /**
     * Sellers
     */
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/', 'UserController@getAll');
        $router->get('/{id}', 'UserController@getOne');
        $router->post('/', 'UserController@create');
        $router->put('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@delete');
    });

    /**
     * Orders
     */
    $router->group(['prefix' => 'orders'], function () use ($router) {
        $router->get('/', 'OrderController@getAll');
        $router->get('/{id}/download', 'PdfController@downloadOrderPdf');
        $router->get('/{id}', 'OrderController@getOne');
        $router->get('/customer/{id}', 'OrderController@getAllByCustomer');
        $router->get('/{id}/products', 'OrderController@getOneByCustomer');
        $router->post('/', 'OrderController@create');
    });

    /**
     * Products
     */
    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/', 'ProductController@getAll');
        $router->get('/{id}', 'ProductController@getOne');
        $router->post('/', 'ProductController@create');
        $router->put('/{id}', 'ProductController@update');
        $router->delete('/{id}', 'ProductController@delete');
    });

    /**
     * Lists
     */
    $router->group(['prefix' => 'lists'], function () use ($router) {
        $router->get('/', 'ListController@getAll');
        $router->get('/{id}', 'ListController@getOne');
        $router->post('/', 'ListController@create');
    });

    /**
     * Categories
     */
    $router->group(['prefix' => 'categories'], function () use ($router) {
        $router->get('/', 'CategoryController@getAll');
        $router->get('/{id}', 'CategoryController@getOne');
        $router->post('/', 'CategoryController@create');
        $router->put('/{id}', 'CategoryController@update');
        $router->delete('/{id}', 'CategoryController@delete');
    });

    /**
     * Auth
     */
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->get('/me', 'AuthController@me');
    });

    /**
     * Reports
     */
    $router->group(['prefix' => 'reports'], function () use ($router) {
        $router->get('products[/{from}/{to}]', 'ReportController@getProductReport');
        $router->get('products/{id:[0-9]+}[/{from}/{to}]', 'ReportController@getProductReportById');

        $router->get('categories[/{from}/{to}]', 'ReportController@getCategoryReport');
        $router->get('categories/{id:[0-9]+}[/{from}/{to}]', 'ReportController@getCategoryReportById');

        $router->get('sellers[/{from}/{to}]', 'ReportController@getSellerReport');
        $router->get('sellers/{id:[0-9]+}[/{from}/{to}]', 'ReportController@getSellerReportById');

        $router->get('customers[/{from}/{to}]', 'ReportController@getCustomerReport');
        $router->get('customers/{id:[0-9]+}[/{from}/{to}]', 'ReportController@getCustomerReportById');

        $router->get('orders[/{from}/{to}]', 'ReportController@getOrderReport');
        $router->get('orders/{id:[0-9]+}[/{from}/{to}]', 'ReportController@getOrderReportById');

        $router->group(['prefix' => 'period'], function () use ($router) {
            $router->get('/{from}/{to}', 'ReportController@getPeriodReport');
        });

        $router->get('{type}/download[/{from}/{to}]', 'PdfController@downloadReportPdf');
    });
});
