<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    private array $currencies = [
        [
            "symbol" => "EUR",
            "name"   => "Euro"
        ],
        [
            "symbol" => "USD",
            "name"   => "United States Dollar"
        ],
        [
            "symbol" => "UYU",
            "name"   => "Uruguayan Peso"
        ]
    ];

    public function run()
    {
        Currency::insert($this->currencies);
    }
}
