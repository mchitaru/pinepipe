<?php

use App\User;
use App\Client;
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
                'email_verified_at' => now(),
                'locale' => 'en'
            ]
        );

        User::$SEED_COMPANY_IDX++;
        User::$SEED_COMPANY_ID = $company->id;

        $company->initCompanyDefaults();
    }
}
