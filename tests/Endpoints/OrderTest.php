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
        $this->get(OrderTest::URL . '1')->response->getContent();
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
            'customer' => '1',
            'order' => [
                ['id' => 1, 'qnt' => 2],
                ['id' => 2, 'qnt' => 3]
            ]
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
            'customer' => '12345',
            'order' => [
                ['id' => 2, 'qnt' => 3]
            ]
        ]);
        $this->assertResponseStatus(404);
    }
}