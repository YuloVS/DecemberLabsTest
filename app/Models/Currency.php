<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = [
        "symbol", "name"
    ];

    public function account()
    : HasMany
    {
        return $this->hasMany(Account::class);
    }
}
