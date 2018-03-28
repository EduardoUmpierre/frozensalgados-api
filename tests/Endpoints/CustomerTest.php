<?php

namespace Tests\Endpoints;

use App\Order;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class CustomerTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class CustomerTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/customers/';

    /**
     *
     */
    public function testGettingAllCustomers()
    {
        // Request without authentication
        $this->get(CustomerTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all customers
        $this->get(CustomerTest::URL . '?all=1');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name'
            ]
        ]);

        // Creates 3 orders
        factory(Order::class, 3)->create(['user_id' => $user->id]);

        // Get all customers that have a order with the authenticated user
        $this->get(CustomerTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'address', 'id', 'name', 'phone'
            ]
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificCustomer()
    {
        // Request without authentication
        $this->get(CustomerTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get one customer without lists
        $this->get(CustomerTest::URL . $user->id);
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name', 'phone', 'address', 'city', 'cep', 'district', 'cnpj', 'address_number'
        ]);

        // Get one customer with lists
        $this->get(CustomerTest::URL . $user->id . '?lists=true');
        $this->assertResponseStatus(200);

        // Test json response
        $this->seeJsonStructure([
            'id', 'name', 'phone', 'address', 'city', 'cep', 'district', 'cnpj', 'address_number', 'lists'
        ]);

        // Accessing invalid user should give 404
        $this->get(CustomerTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testCreatingCustomer()
    {
        // Request without authentication
        $this->post(CustomerTest::URL, []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->make();
        $this->actingAs($user);

        // Empty data
        $this->post(CustomerTest::URL, []);
        $this->assertResponseStatus(422);

        // Invalid request - required fields are missing
        $this->post(CustomerTest::URL, [
            'name' => 'Test'
        ]);
        $this->assertResponseStatus(422);

        // Valid request
        $this->post(CustomerTest::URL, [
            'name' => 'Teste',
            'cnpj' => '12345',
            'cep' => '12345',
            'address' => 'Rua 123',
            'address_number' => '12345',
            'city' => 'Porto Alegre',
            'district' => 'Rio Branco'
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - same CNPJ
        $this->post(CustomerTest::URL, [
            'name' => 'Teste',
            'cnpj' => '12345',
            'cep' => '12345',
            'address' => 'Rua 123',
            'address_number' => '12345',
            'city' => 'Porto Alegre',
            'district' => 'Rio Branco'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     *
     */
    public function testUpdatingCustomer()
    {
        // Request without authentication
        $this->put(CustomerTest::URL . '1', []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->put(CustomerTest::URL . $user->id, [
            'name' => '123456',
            'cnpj' => '123',
            'cep' => '123',
            'address' => '123',
            'address_number' => '123',
            'city' => '123',
            'district' => '123'
        ]);
        $this->assertResponseOk();

        // Invalid request - required fields missing
        $this->put(CustomerTest::URL . $user->id, [
            'name' => '123456',
            'cnpj' => '123',
            'cep' => '123'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - no customer id
        $this->put(CustomerTest::URL, [
            'name' => '123456',
            'cnpj' => '123',
            'cep' => '123',
            'address' => '123',
            'address_number' => '123',
            'city' => '123',
            'district' => '123'
        ]);
        $this->assertResponseStatus(405);

        // Invalid id
        $this->put(CustomerTest::URL . '234324', [
            'name' => '123456',
            'cnpj' => '1234',
            'cep' => '123',
            'address' => '123',
            'address_number' => '123',
            'city' => '123',
            'district' => '123'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testDeletingCustomer()
    {
        // Request without authentication
        $this->delete(CustomerTest::URL . '12345');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Invalid call without id
        $this->delete(CustomerTest::URL);
        $this->assertResponseStatus(405);

        // Valid request
        $this->delete(CustomerTest::URL . $user->id);
        $this->assertResponseStatus(204);

        // Invalid id
        $this->delete(CustomerTest::URL . '13232323');
        $this->assertResponseStatus(404);
    }
}