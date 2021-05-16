<?php

namespace Database\Factories;

use App\Helpers\CurrencyConverter;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    : array
    {
        return [
            "origin_account_id"      => $origin = Account::factory()->make(),
            "destination_account_id" => $destiny = Account::factory()->make(),
            "amount"                 => $amount = $this->faker->randomFloat(4, 0, $origin->balance),
            "description"            => $this->faker->text(140),
            "converted"              => CurrencyConverter::convertTransaction($origin, $destiny, $amount)
        ];
    }
}
