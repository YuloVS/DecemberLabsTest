<?php

namespace Database\Seeders;

use App\Models\Account;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::all()->each(function($account){
            for($i = 1; $i <= rand(1, 16); $i++)
            {
                if(Factory::create()->boolean())
                {
                    $account->makeTransaction(
                        Account::all()->whereNotIn("id", $account->id)->random(),
                        Factory::create()->randomFloat(4, 0, $account->balance - $account->balance * config("transaction_commission") ),
                        Factory::create()->text(140)
                    );
                }
            }

        });

    }
}
