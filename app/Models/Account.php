<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    : HasOne
    {
        return $this->hasOne(Currency::class);
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
}
