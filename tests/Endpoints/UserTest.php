<?php

namespace Tests\Endpoints;

use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Passport\Token;

/**
 * Class UserTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class UserTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/users/';

    /**
     *
     */
    public function testGettingAllUsers()
    {
        // Request without authentication
        $this->get(UserTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get all users
        $this->get( UserTest::URL);
        $this->assertResponseOk();

        // Test json response
        $this->seeJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => (string)$user->email,
            'role' => (string)$user->role,
            'is_active' => $user->is_active
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificUser()
    {
        // Request without authentication
        $this->get(UserTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get one user
        $this->get(UserTest::URL . $user->id);
        $this->assertResponseStatus(200);

        // Test json response
        $this->seeJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => (string)$user->email,
            'role' => (string)$user->role,
            'is_active' => $user->is_active
        ]);

        // Accessing invalid user should give 404
        $this->call('GET', UserTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testCreatingUser()
    {
        // Request without authentication
        $this->post(UserTest::URL, []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->make();
        $this->actingAs($user);

        // Empty data
        $this->post(UserTest::URL, []);
        $this->assertResponseStatus(422);

        // Valid request
        $this->post(UserTest::URL, [
            'name' => 'Test',
            'email' => 'test@test.com',
            'cpf' => '123456789',
            'role' => '1',
            'password' => '123',
            'passwordRepeat' => '123',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(201);

        // Same e-mail as the last one
        $this->post(UserTest::URL, [
            'name' => 'Test',
            'email' => 'test@test.com',
            'cpf' => '11111',
            'role' => '1',
            'password' => '123',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(422);

        // Wrong password repeat
        $this->post(UserTest::URL, [
            'name' => 'Test',
            'email' => 'test1@test.com',
            'cpf' => '11112',
            'role' => '1',
            'password' => '123',
            'passwordRepeat' => '321',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(405);

        // No password repeat sent
        $this->post(UserTest::URL, [
            'name' => 'Test',
            'email' => 'test2@test.com',
            'cpf' => '11113',
            'role' => '1',
            'password' => '123',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     *
     */
    public function testUpdatingUser()
    {
        // Request without authentication
        $this->put(UserTest::URL . '1', []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->put(UserTest::URL . $user->id, [
            'name' => 'Eduardo',
            'email' => '12312312312',
            'cpf' => '12312312312',
            'role' => '2',
            'is_active' => 1
        ]);
        $this->assertResponseOk();

        // Valid request
        $this->put(UserTest::URL . $user->id, [
            'name' => 'Eduardo',
            'email' => '123123123123',
            'cpf' => '123123123123',
            'role' => '2',
            'password' => '123',
            'passwordRepeat' => '123',
            'is_active' => 1
        ]);
        $this->assertResponseOk();

        // No password repeat
        $this->put(UserTest::URL . $user->id, [
            'name' => 'Eduardo',
            'email' => '12312312312',
            'cpf' => '12312312312',
            'role' => '2',
            'password' => '123',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(405);

        // Wrong password repeat
        $this->put(UserTest::URL . $user->id, [
            'name' => 'Eduardo',
            'email' => '12312312312',
            'cpf' => '12312312312',
            'role' => '2',
            'password' => '123',
            'passwordRepeat' => '321',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(405);

        // Invalid id
        $this->put(UserTest::URL . '234324', [
            'name' => 'Eduardo',
            'email' => '321312312312',
            'cpf' => '321312312312',
            'role' => '2',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(404);

        // Without id
        $this->put(UserTest::URL, [
            'name' => 'Eduardo',
            'email' => '321312312312',
            'cpf' => '321312312312',
            'role' => '2',
            'is_active' => 1
        ]);
        $this->assertResponseStatus(405);
    }

    /**
     *
     */
    public function testDeletingUser()
    {
        // Request without authentication
        $this->delete(UserTest::URL . '12345');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Invalid call without id
        $this->delete(UserTest::URL);
        $this->assertResponseStatus(405);

        // Valid request
        $this->delete(UserTest::URL . $user->id);
        $this->assertResponseStatus(204);

        // Invalid id
        $this->delete(UserTest::URL . '13232323');
        $this->assertResponseStatus(404);
    }
}
