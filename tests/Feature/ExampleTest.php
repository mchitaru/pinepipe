<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;

class ExampleTest extends TestCase
{
    // use RefreshDatabase;
    // use WithoutMiddleware;

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
