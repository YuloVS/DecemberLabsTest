<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
