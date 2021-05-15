<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseAccountsRegistry extends Model
{
    use HasFactory;

    public function account()
    : BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public static function getAccountOfCurrency(int $commissionCurrency)
    {
        return self::whereHas("account", function($query) use ($commissionCurrency){
            return $query->whereCurrencyId($commissionCurrency);
        })->first()->account;
    }
}
