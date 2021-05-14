<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Currency extends Model
{
    protected $fillable = [
        "symbol", "name"
    ];

    public function account()
    : BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
