<?php

namespace App\Models;

use App\Helpers\CurrencyConverter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function makeTransaction(Account $destinyAccount, float $amount, string $description, $complete = null)
    : Model
    {
        if($amount > $this->balance + $this->balance * config("transaction_commission"))
        {
            throw new \Exception("Insufficient funds");
        }
        DB::beginTransaction();
        $transaction = $this->madeTransactions()->create([
                                                             "destination_account_id" => $destinyAccount->id,
                                                             "amount"                 => $amount,
                                                             "description"            => $description,
                                                             "converted"              => CurrencyConverter::convertTransaction($this, $destinyAccount, $amount),
                                                             "complete"               => Carbon::parse($complete)
                                                         ]);
        $this->updateBalances($transaction);
        return $transaction;
    }

    public function updateBalances(Model $transaction)
    {
        $commissionCurrency = $this->currency_id;
        $amountWithComission = $transaction->amount;
        $commissionAmount = 0;
        if($this->user->isNot($transaction->destinationAccount->user))
        {
            $commissionAmount = config("transaction_commission") * $transaction->amount;
            $amountWithComission += $commissionAmount;
        }
        $this->update(["balance" => $this->balance - $amountWithComission]);
        $transaction->destinationAccount()->update(["balance" => $transaction->destinationAccount->balance + $transaction->converted]);
        $houseAccount = HouseAccountsRegistry::getAccountOfCurrency($commissionCurrency);
        $houseAccount->update(["balance" => $houseAccount->balance + $commissionAmount]);
        $transaction->update(["complete" => Carbon::now()]);
        DB::commit();
    }
}
