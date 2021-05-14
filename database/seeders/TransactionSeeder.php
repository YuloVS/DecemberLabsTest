<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
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
        $accounts = Account::all()->each(function($account){
            $transactions = Transaction::factory()->count(100)
                ->make([
                           "origin_account_id"      => $account,
                           "destination_account_id" => Account::all()->whereNotIn("id", $account->id)->random()
                       ])->toArray();
            $account->transactions()->insert($transactions);
        });
    }
}
