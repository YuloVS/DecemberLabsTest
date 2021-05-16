<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    : array
    {
        return [
            "user_id" => User::factory(),
            "currency_id" => Currency::inRandomOrder()->first(),
            "balance" => $this->faker->randomFloat(4, 0, 100000)
        ];
    }
}
