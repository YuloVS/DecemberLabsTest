<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accounts()
    : HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function transactions($from=null, $to=null, $sourceAccountId=null)
    : array
    {
        $madeTransactions = [];
        $receivedTransactions = [];

        is_null($sourceAccountId) ? $accounts = $this->accounts : $accounts = $this->accounts->where("id", "=", $sourceAccountId);

        foreach($accounts as $account)
        {
            if(!is_null($from) && !is_null($to))
            {
                $iMadeTransactions = $account->madeTransactions->whereBetween("complete", [$from, $to]);
                $iReceivedTransactions = $account->receivedTransactions->whereBetween("complete", [$from, $to]);
            }
            elseif(!is_null($from) && is_null($to))
            {
                $iMadeTransactions = $account->madeTransactions->where("complete", ">", $from);
                $iReceivedTransactions = $account->receivedTransactions->where("complete", ">", $from);
            }
            else
            {
                $iMadeTransactions = $account->madeTransactions;
                $iReceivedTransactions = $account->receivedTransactions;
            }

            foreach($iMadeTransactions as $madeTransaction)
            {
                array_push($madeTransactions, $madeTransaction);
            }
            foreach($iReceivedTransactions as $receivedTransaction)
            {
                array_push($receivedTransactions, $receivedTransaction);
            }
        }

        return [
            "transactions" => $madeTransactions + $receivedTransactions,
            "made_transactions" => $madeTransactions,
            "received_transactions" => $receivedTransactions
        ];
    }
}
