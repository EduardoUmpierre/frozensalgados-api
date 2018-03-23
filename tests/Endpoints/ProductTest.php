<?php

namespace Tests\Endpoints;

use App\Order;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class ProductTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class ProductTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/products/';

    /**
     *
     */
    public function testGettingAllProducts()
    {
        // Request without authentication
        $this->get(ProductTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all products
        $this->get(ProductTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'price'
            ]
        ]);

        // Get all customers that have a order with the authenticated user
        $this->get(ProductTest::URL . '?id=1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'price'
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificProduct()
    {
        // Request without authentication
        $this->get(ProductTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get one customer without lists
        $this->get(ProductTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name', 'price'
        ]);

        // Accessing invalid user should give 404
        $this->get(ProductTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testCreatingProduct()
    {
        // Request without authentication
        $this->post(ProductTest::URL, []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->make();
        $this->actingAs($user);

        // Empty data
        $this->post(ProductTest::URL, []);
        $this->assertResponseStatus(422);

        // Invalid request - required fields are missing
        $this->post(ProductTest::URL, [
            'name' => 'Test'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - wrong field type
        $this->post(ProductTest::URL, [
            'name' => 'Teste',
            'price' => 'abc'
        ]);
        $this->assertResponseStatus(422);

        // Valid request
        $this->post(ProductTest::URL, [
            'name' => 'Teste',
            'price' => '12'
        ]);
        $this->assertResponseStatus(201);
    }

    /**
     *
     */
    public function testUpdatingProduct()
    {
        // Request without authentication
        $this->put(ProductTest::URL . '1', []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->put(ProductTest::URL . '1', [
            'name' => 'Produto 1',
            'price' => '123'
        ]);
        $this->assertResponseOk();

        // Invalid request - required fields missing
        $this->put(ProductTest::URL . '1', [
            'name' => 'Produto 2'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - no product id
        $this->put(ProductTest::URL, [
            'name' => 'Produto 3',
            'price' => '123'
        ]);
        $this->assertResponseStatus(405);

        // Invalid id
        $this->put(ProductTest::URL . '234324', [
            'name' => '123456',
            'price' => '1234'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testDeletingProduct()
    {
        // Request without authentication
        $this->delete(ProductTest::URL . '12345');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Invalid call without id
        $this->delete(ProductTest::URL);
        $this->assertResponseStatus(405);

        // Valid request
        $this->delete(ProductTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid id
        $this->delete(ProductTest::URL . '13232323');
        $this->assertResponseStatus(404);
    }
}