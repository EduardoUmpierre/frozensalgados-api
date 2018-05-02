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
class CategoryTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/categories/';

    /**
     *
     */
    public function testGettingAllCategories()
    {
        // Request without authentication
        $this->get(CategoryTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all orders with the authenticated user
        $this->get(CategoryTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name'
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificCategory()
    {
        // Request without authentication
        $this->get(CategoryTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get one order
        $this->get(CategoryTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name'
        ]);

        // Accessing invalid order should give 404
        $this->get(CategoryTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
    *
    */
    public function testCreatingCategory()
    {
        // Request without authentication
        $this->post(CategoryTest::URL, []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->make();
        $this->actingAs($user);

        // Empty data
        $this->post(CategoryTest::URL, []);
        $this->assertResponseStatus(422);

        // Valid request
        $this->post(CategoryTest::URL, [
            'name' => 'Teste'
        ]);
        $this->assertResponseStatus(201);
    }

    /**
     *
     */
    public function testUpdatingCategory()
    {
        // Request without authentication
        $this->put(CategoryTest::URL . '1', []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->put(CategoryTest::URL . '1', [
            'name' => 'Produto 1'
        ]);
        $this->assertResponseOk();

        // Invalid id
        $this->put(CategoryTest::URL . '234324', [
            'name' => '123456'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testDeletingCategory()
    {
        // Request without authentication
        $this->delete(CategoryTest::URL . '12345');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->delete(CategoryTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid id
        $this->delete(CategoryTest::URL . '13232323');
        $this->assertResponseStatus(404);
    }
}