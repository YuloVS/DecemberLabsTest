<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountCollection extends ResourceCollection
{
    public function toArray($request)
    : array
    {
        return parent::toArray($request);
    }
}
