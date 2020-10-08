<?php

use App\User;
use Illuminate\Database\Seeder;
use App\Permission;

class UserSeeder extends Seeder
{
 
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create(
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('metallicarulz'),
                'type' => 'super admin',
                'avatar' => '',
                'email_verified_at' => now(),
            ]
        );
    }
}
