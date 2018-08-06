<?php

namespace Tests\Endpoints;

use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class CategoryTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class ReportTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/reports/';

    /**
     *
     */
    public function testGettingProductReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'products');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'products');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'list' => [
                '*' => [
                    'id', 'name', 'total', 'quantity'
                ]
            ]
        ]);

        // Request with authentication
        $this->get(ReportTest::URL . 'products/2018-02-01/2018-02-28T13:00:00Z');
        $this->assertResponseOk();

        $this->get(ReportTest::URL . 'products/2018-02/2018');
        $this->assertResponseStatus(422);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'products/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'list' => [
                '*' => [
                    'id', 'name', 'total', 'quantity'
                ]
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificProductReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'products/1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'products/1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'quantity', 'total', 'list' => []
            ]
        ]);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'products/1/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'quantity', 'total', 'list' => []
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingCategoryReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'categories');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'categories');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'list' => [
                '*' => [
                    'id', 'name', 'total', 'quantity'
                ]
            ]
        ]);

        // Request without authentication
        $this->get(ReportTest::URL . 'categories/2018-02-01/2018-02-28T13:00:00Z');
        $this->assertResponseOk();

        $this->get(ReportTest::URL . 'categories/2018-02/2018');
        $this->assertResponseStatus(422);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'categories/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'list' => [
                '*' => [
                    'id', 'name', 'total', 'quantity'
                ]
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificCategoryReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'categories/1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'categories/1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'name', 'total'
        ]);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'categories/1/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'name', 'total'
        ]);
    }

    /**
     *
     */
    public function testGettingSellerReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'sellers');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all sellers with the authenticated user
        $this->get(ReportTest::URL . 'sellers');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'list' => [
                '*' => [
                    'id', 'name', 'quantity', 'total', 'list'
                ]
            ]
        ]);

        // Request without authentication
        $this->get(ReportTest::URL . 'sellers/2018-02-01/2018-02-28T13:00:00Z');
        $this->assertResponseOk();

        $this->get(ReportTest::URL . 'sellers/2018-02/2018');
        $this->assertResponseStatus(422);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'sellers/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'list' => [
                '*' => [
                    'id', 'name', 'quantity', 'total', 'list'
                ]
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificSellerReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'sellers/1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'sellers/1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'name', 'total'
        ]);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'sellers/1/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'name', 'total'
        ]);
    }

    /**
     *
     */
    public function testGettingCustomerReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'customers');
        $this->assertResponseStatus(401);
    }

    /**
     *
     */
    public function testGettingSpecificCustomerReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'customers/1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'customers/1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'name', 'total'
        ]);

        // Get all orders with the authenticated user
        $this->get(ReportTest::URL . 'customers/1/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'id', 'name', 'total'
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificPeriodReport()
    {
        // Request without authentication
        $this->get(ReportTest::URL . 'period/2018-02-01/2018-02-28');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get period report with the authenticated user
        $this->get(ReportTest::URL . 'period/2018-02-01/2018-02-28');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            'orders_quantity', 'orders_total', 'customers_quantity', 'average_ticket', 'products_list',
            'categories_list', 'sellers_list'
        ]);
    }
}
