<?php

namespace App\Models;

use App\Helpers\Fixerio;
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

    private function shouldConvertTransaction(Account $destinyAccount)
    : bool
    {
        return !($this->currency_id == $destinyAccount->currency_id);
    }

    public function convertTransaction(Account $destinyAccount, float $amount)
    : float
    {
        if($this->shouldConvertTransaction($destinyAccount))
        {
            return $amount / Fixerio::currencyRate($this->currency->symbol) * Fixerio::currencyRate($destinyAccount->currency->symbol);
        }
        return $amount;
    }

    public function makeTransaction(Account $destinyAccount, float $amount, string $description)
    : Model
    {
        return $this->madeTransactions()->create([
                                                     "destination_account_id" => $destinyAccount->id,
                                                     "amount"                 => $amount,
                                                     "description"            => $description,
                                                     "converted"              => $this->convertTransaction($destinyAccount, $amount)
                                                 ]);
    }
}
