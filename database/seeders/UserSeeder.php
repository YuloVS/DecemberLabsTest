<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->hasAccounts(3)->create();
        User::factory()->hasAccounts(2)->create();
        User::factory()->hasAccounts()->create();
    }
}
