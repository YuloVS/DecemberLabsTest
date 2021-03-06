<?php

namespace App\Models;

use App\Events\TransactionCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "destination_account_id", "amount", "description", "converted", "complete"
    ];

    public function originAccount()
    : BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function destinationAccount()
    : BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
