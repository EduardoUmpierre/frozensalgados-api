<?php

namespace Tests\Endpoints;

use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Passport\Token;

/**
 * Class UsersTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class UsersTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/users/';

    /**
     *
     */
    public function testGettingAllUsers()
    {
        // Request without authentication
        $this->call('GET', UsersTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $user->withAccessToken(new Token(['scopes' => ['*']]));

        $this->actingAs($user);

        // Get all users
        $this->call('GET', UsersTest::URL);
        $this->assertResponseOk();

        // Test json response
        $this->seeJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => (string)$user->email,
            'role' => (string)$user->role
        ]);
    }

    /**
     *
     */
    public function testGettingSpecificUser()
    {
        // Request without authentication
        $this->call('GET', UsersTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $user->withAccessToken(new Token(['scopes' => ['*']]));

        $this->actingAs($user);

        // Get one user
        $this->call('GET', UsersTest::URL . $user->id);
        $this->assertResponseStatus(200);

        // Teste json response
        $this->seeJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => (string)$user->email,
            'role' => (string)$user->role
        ]);

        // Accessing invalid user should give 404
        $this->call('GET', UsersTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testCreatingUser()
    {
        // Request without authentication
        $this->call('POST', UsersTest::URL, []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->make();
        $user->withAccessToken(new Token(['scopes' => ['*']]));
        $this->actingAs($user);

        // Empty data
        $this->call('POST', UsersTest::URL, []);
        $this->assertResponseStatus(422);

        // Valid request
        $this->call('POST', UsersTest::URL, [
            'name' => 'Test',
            'email' => 'test@test.com',
            'cpf' => '123456789',
            'role' => '1',
            'password' => '123',
            'passwordRepeat' => '123'
        ]);
        $this->assertResponseStatus(201);

        // Same e-mail as the last one
        $this->call('POST', UsersTest::URL, [
            'name' => 'Test',
            'email' => 'test@test.com',
            'cpf' => '11111',
            'role' => '1',
            'password' => '123'
        ]);
        $this->assertResponseStatus(422);

        // Wrong password repeat
        $this->call('POST', UsersTest::URL, [
            'name' => 'Test',
            'email' => 'test1@test.com',
            'cpf' => '11112',
            'role' => '1',
            'password' => '123',
            'passwordRepeat' => '321'
        ]);
        $this->assertResponseStatus(405);

        // No password repeat sent
        $this->call('POST', UsersTest::URL, [
            'name' => 'Test',
            'email' => 'test2@test.com',
            'cpf' => '11113',
            'role' => '1',
            'password' => '123'
        ]);
        $this->assertResponseStatus(405);
    }

    /**
     *
     */
    public function testUpdatingUser()
    {
        // Request without authentication
        $this->call('PUT', UsersTest::URL . '1', []);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $user->withAccessToken(new Token(['scopes' => ['*']]));
        $this->actingAs($user);

        // Valid request
        $this->call('PUT', UsersTest::URL . $user->id, [
            'name' => 'Eduardo',
            'email' => '12312312312',
            'cpf' => '12312312312',
            'role' => '2'
        ]);
        $this->assertResponseOk();

        // No password repeat
        $this->call('PUT', UsersTest::URL . $user->id, [
            'name' => 'Eduardo',
            'email' => '12312312312',
            'cpf' => '12312312312',
            'role' => '2',
            'password' => '123'
        ]);
        $this->assertResponseStatus(405);

        // Wrong password repeat
        $this->call('PUT', UsersTest::URL . $user->id, [
            'name' => 'Eduardo',
            'email' => '12312312312',
            'cpf' => '12312312312',
            'role' => '2',
            'password' => '123',
            'passwordRepeat' => '321'
        ]);
        $this->assertResponseStatus(405);

        // Invalid id
        $this->call('PUT', UsersTest::URL . '234324', [
            'name' => 'Eduardo',
            'email' => '321312312312',
            'cpf' => '321312312312',
            'role' => '2'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     *
     */
    public function testDeletingUser()
    {
        // Request without authentication
        $this->call('DELETE', UsersTest::URL . '12345');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $user->withAccessToken(new Token(['scopes' => ['*']]));
        $this->actingAs($user);

        // Valid request
        $this->call('DELETE', UsersTest::URL . $user->id);
        $this->assertResponseStatus(204);

        // Invalid id
        $this->call('DELETE', UsersTest::URL . '13232323');
        $this->assertResponseStatus(404);
    }
}