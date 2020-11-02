<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    public static $USER1 = 2;
    public static $USER2 = 3;

    public function testHomepageTest()
    {
        $user = User::find(UserTest::$USER1); // find specific user
        assert($user);

        $response = $this->actingAs($user)
                            ->get('/');

        $response->assertStatus(200);
    }

    public function testCompanyDefaultsTest()
    {
        $user_id = UserTest::$USER1;

        $user = User::find($user_id); // find specific user
        assert($user);

        $this->assertDatabaseHas('stages', ['created_by' => $user_id]);
        $this->assertDatabaseHas('clients', ['created_by' => $user_id]);
        $this->assertDatabaseHas('contacts', ['created_by' => $user_id]);
        $this->assertDatabaseHas('leads', ['created_by' => $user_id]);
        $this->assertDatabaseHas('projects', ['created_by' => $user_id]);
        $this->assertDatabaseHas('invoices', ['created_by' => $user_id]);
    }

    public function testDeleteUserTest()
    {
        $user_id = UserTest::$USER1;
        $user = User::find($user_id); // find specific user
        assert($user);

        $user->delete();

        //defaults
        $this->assertDatabaseMissing('stages', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('clients', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('contacts', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('leads', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('projects', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('invoices', ['created_by' => $user_id]);

        //other
        $this->assertDatabaseMissing('events', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('tasks', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('timesheets', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('checklists', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('comments', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('expenses', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('google_accounts', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('activities', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('subscriptions', ['user_id' => $user_id]);
        $this->assertDatabaseMissing('taxes', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('tags', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('articles', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('categories', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('company_settings', ['created_by' => $user_id]);
        $this->assertDatabaseMissing('media', ['created_by' => $user_id]);

        $user_id = UserTest::$USER2;

        $user = User::find($user_id); // find specific user
        assert($user);

        $this->assertDatabaseHas('stages', ['created_by' => $user_id]);
        $this->assertDatabaseHas('clients', ['created_by' => $user_id]);
        $this->assertDatabaseHas('contacts', ['created_by' => $user_id]);
        $this->assertDatabaseHas('leads', ['created_by' => $user_id]);
        $this->assertDatabaseHas('projects', ['created_by' => $user_id]);
        $this->assertDatabaseHas('invoices', ['created_by' => $user_id]);
    }
}
