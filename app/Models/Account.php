<?php

namespace App\Models;

use App\Helpers\CurrencyConverter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "currency_id", "balance"
    ];

    public function user()
    : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    : BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function receivedTransactions()
    : HasMany
    {
        return $this->hasMany(Transaction::class, "destination_account_id", "id");
    }

    public function madeTransactions()
    : HasMany
    {
        return $this->hasMany(Transaction::class, "origin_account_id", "id");
    }

    public function houseAccountsRegistry()
    : HasMany
    {
        return $this->hasMany(HouseAccountsRegistry::class);
    }

    /**
     * @throws \Exception
     */
    public function makeTransaction(Account $destinyAccount, float $amount, string $description)
    : Model
    {
        if($amount > $this->balance * config("transaction_commission"))
            throw new \Exception("Insufficient funds");
        return $this->madeTransactions()->create([
                                                     "destination_account_id" => $destinyAccount->id,
                                                     "amount"                 => $amount,
                                                     "description"            => $description,
                                                     "converted"              => CurrencyConverter::convertTransaction($this, $destinyAccount, $amount)
                                                 ]);
    }
}
