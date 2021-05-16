<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\HouseAccountsRegistry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HouseAccountsRegistrySeeder extends Seeder
{
    public function run()
    {
        /** @var User $admin */
        $admin = User::factory()->create([
                                             "name"  => "Admin",
                                             "email" => "admin@test.com",
                                             "password" => Hash::make("admin")
                                         ]);
        foreach(Currency::all() as $currency)
        {
            $account = $admin->accounts()->create([
                                           "currency_id" => $currency->id
                                       ]);
            HouseAccountsRegistry::create([
                "account_id" => $account->id
                                          ]);
        }
        $admin->createToken("admin");
    }
}
