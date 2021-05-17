<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AccountResource extends JsonResource
{
    public function toArray($request)
    : array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "user" => $this->user->name,
            "currency_id" => $this->currency_id,
            "currency" => $this->currency->symbol,
            "balance" => $this->when(Auth::user()->is($this->user), $this->balance)
        ];
    }
}
