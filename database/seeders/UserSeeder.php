<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->hasAccounts()->create([
                                                   "email"    => "user1@test.com",
                                                   "password" => Hash::make("user1")
                                               ])->createToken("user1");
        User::factory()->hasAccounts(2)->create([
                                                    "email"    => "user2@test.com",
                                                    "password" => Hash::make("user2")
                                                ])->createToken("user2");
        User::factory()->hasAccounts(3)->create([
                                                    "email"    => "user3@test.com",
                                                    "password" => Hash::make("user3")
                                                ])->createToken("user3");
    }
}
