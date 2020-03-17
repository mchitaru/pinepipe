<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $user = User::find(1); // find specific user
        assert($user);

        $response = $this->actingAs($user)
                            ->get('/');

        $response->assertStatus(200);
    }
}
