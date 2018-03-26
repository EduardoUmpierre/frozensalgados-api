<?php

namespace Tests\Endpoints;

use App\ListModel;
use App\Order;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Passport\Token;

/**
 * Class ListTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class ListTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/lists/';

    /**
     *
     */
    public function testGettingAllLists()
    {
        // Request without authentication
        $this->get(ListTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all products
        $this->get(ListTest::URL);
        $this->assertResponseStatus(405);

        factory(ListModel::class, 3)->create(['user_id' => $user->id, 'customer_id' => 1]);

        // Get all customers that have a order with the authenticated user
        $this->get(ListTest::URL . '?customer=1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'title', 'product_count', 'list_product'
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificList()
    {
        // Request without authentication
        $this->get(ListTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        factory(ListModel::class, 3)->create(['user_id' => $user->id, 'customer_id' => 1]);

        // Get one customer
        $this->get(ListTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'title', 'product_count', 'list_product'
        ]);

        // Accessing invalid user should give 404
        $this->get(ListTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testCreatingList()
    {
        // Request without authentication
        $this->post(ListTest::URL, []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = User::query()->findOrFail(1);
        $this->be($user);

        // Valid request
        $this->post(ListTest::URL, [
            'title' => 'Teste',
            'customer' => '1',
            'order' => [
                ['id' => 1, 'qnt' => 2],
                ['id' => 2, 'qnt' => 3]
            ]
        ]);
        $this->assertResponseStatus(201);

        // Empty data
        $this->post(ListTest::URL, []);
        $this->assertResponseStatus(422);

        // Invalid request - required fields are missing
        $this->post(ListTest::URL, [
            'title' => 'Test'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - invalid customer id
        $this->post(ListTest::URL, [
            'title' => 'Teste',
            'customer' => '12345',
            'order' => [
                ['id' => 2, 'qnt' => 3]
            ]
        ]);
        $this->assertResponseStatus(404);
    }
}