<?php

namespace Tests\Endpoints;

use App\ListModel;
use App\Order;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class OrderTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class OrderTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/orders/';

    /**
     *
     */
    public function testGettingAllOrders()
    {
        // Request without authentication
        $this->get(OrderTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        factory(Order::class, 3)->create(['user_id' => $user->id, 'customer_id' => 1]);

        // Get all orders with the authenticated user
        $this->get(OrderTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'total', 'status', 'created_at', 'user_id', 'customer' => ['id', 'name']
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingAllCustomerOrders()
    {
        // Request without authentication
        $this->get(OrderTest::URL . 'customer/1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(OrderTest::URL  . 'customer/1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'created_at'
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificOrder()
    {
        // Request without authentication
        $this->get(OrderTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        factory(Order::class, 3)->create(['user_id' => $user->id, 'customer_id' => 1]);

        // Get one order
        $this->get(OrderTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'total', 'status', 'created_at', 'user_id', 'customer' => ['id', 'name', 'phone', 'address'],
            'order_product'
        ]);

        // Accessing invalid order should give 404
        $this->get(OrderTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testGettingSpecificCustomerOrder()
    {
        // Request without authentication
        $this->get(OrderTest::URL . '1/products');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        factory(Order::class, 3)->create(['user_id' => $user->id, 'customer_id' => 1]);

        // Get one order
        $this->get(OrderTest::URL . '1/products')->response->getContent();
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'order_product'
        ]);

        // Accessing invalid order should give 404
        $this->get(OrderTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testCreatingOrder()
    {
        // Request without authentication
        $this->post(OrderTest::URL, []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = User::query()->findOrFail(1);
        $this->be($user);

        // Valid request
        $this->post(OrderTest::URL, [
            'customer' => [
                'id' => '1'
            ],
            'order' => [
                ['id' => 1, 'qnt' => 2, 'price' => 50],
                ['id' => 2, 'qnt' => 3, 'price' => 50]
            ],
            'status' => '1',
            'payment_method' => '1',
            'payment_date' => '2018-04-16T19:53:44-03:00',
            'delivery_date' => '2018-04-16T19:53:44-03:00',
            'installments' => '2'
        ]);
        $this->assertResponseStatus(201);

        // Empty data
        $this->post(OrderTest::URL, []);
        $this->assertResponseStatus(422);

        // Invalid request - required fields are missing
        $this->post(OrderTest::URL, [
            'customer' => '1'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - invalid customer id
        $this->post(OrderTest::URL, [
            'customer' => [
                'id' => '1000'
            ],
            'order' => [
                ['id' => 1, 'qnt' => 2],
                ['id' => 2, 'qnt' => 3]
            ],
            'status' => '1',
            'payment_method' => '1',
            'payment_date' => '2018-04-16T19:53:44-03:00',
            'delivery_date' => '2018-04-16T19:53:44-03:00',
            'installments' => '2'
        ]);
        $this->assertResponseStatus(404);
    }
}
