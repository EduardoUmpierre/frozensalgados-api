<?php

namespace Tests\Endpoints;

use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class CustomerTest
 *
 * Inspired in https://github.com/hasib32/rest-api-with-lumen/blob/master/tests/Endpoints/UsersTest.php
 *
 * @package Tests\Endpoints
 */
class AuthTest extends \TestCase
{
    use DatabaseMigrations;

    const URL = '/api/v1/auth/me';

    /**
     *
     */
    public function testGettingMe()
    {
        // Request without authentication
        $this->get(AuthTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Get one customer without lists
        $this->get(AuthTest::URL);
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name', 'role', 'cpf'
        ]);
    }
}