<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PlanSeeder::class);
        $this->call(UserSeeder::class);

        for($idx = 1; $idx <= User::$SEED_COMPANY_COUNT; $idx++)
        {
            $this->call(CompanySeeder::class);

            $this->call(ClientSeeder::class);
            $this->call(ContactSeeder::class);
            $this->call(LeadSeeder::class);
            $this->call(EventSeeder::class);
            $this->call(ProjectSeeder::class);
            $this->call(UserProjectSeeder::class);
            $this->call(TaskSeeder::class);
            $this->call(UserTaskSeeder::class);
            $this->call(TaxSeeder::class);
            $this->call(InvoiceSeeder::class);
            $this->call(ExpenseSeeder::class);
            $this->call(TimesheetSeeder::class);
        }
     }
}
