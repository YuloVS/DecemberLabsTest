<?php


namespace App\Helpers;


use App\Models\Account;

class CurrencyConverter
{
    private static function shouldConvertTransaction(Account $originAccount, Account $destinyAccount)
    : bool
    {
        return !($originAccount->currency_id == $destinyAccount->currency_id);
    }

    public static function convertTransaction(Account $originAccount, Account $destinyAccount, float $amount)
    : float
    {
        if(self::shouldConvertTransaction($originAccount, $destinyAccount))
        {
            return $amount / Fixerio::currencyRate($originAccount->currency->symbol) * Fixerio::currencyRate($destinyAccount->currency->symbol);
        }
        return $amount;
    }
}