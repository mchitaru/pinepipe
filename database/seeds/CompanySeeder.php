<?php

use App\User;
use App\Client;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $company = User::create(
            [
                'name' => $faker->company(),
                'email' => $faker->companyEmail(),
                'password' => Hash::make('1234'),
                'type' => 'company',
                'avatar' => null,
                'created_by' => 1,
                'email_verified_at' => now(),
            ]
        );

        User::$SEED_COMPANY_IDX++;
        User::$SEED_COMPANY_ID = $company->id;

        $role = Role::findByName('company');
        $company->initCompanyDefaults();
        $company->assignRole($role);

        factory(App\User::class, App\Client::$SEED)->create()->each(function ($user) use($company, $faker) {

            $role = Role::findByName('client');
            $user->type = 'client';
            $user->created_by = $company->id;
            $user->client_id = $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Client::$SEED + 1,
                                                        User::$SEED_COMPANY_IDX*Client::$SEED);
            $user->save();
            $user->assignRole($role);
        });

        factory(App\User::class, App\User::$SEED_STAFF_COUNT)->create()->each(function ($user) use($company) {

            $role = Role::findByName('collaborator');
            $user->type = 'collaborator';
            $user->created_by = $company->id;
            $user->save();
            $user->assignRole($role);
        });
    }
}
