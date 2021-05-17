<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    : array
    {
        return [
            "id" => $this->id,
            "origin_account_id" => $this->origin_account_id,
            "origin_account" => new AccountResource($this->originAccount),
            "destination_account_id" => $this->destination_account_id,
            "destination_account" => new AccountResource($this->destinationAccount),
            "amount" => $this->amount,
            "description" => $this->description,
            "converted" => $this->converted,
            "complete" => $this->complete
        ];
    }
}
