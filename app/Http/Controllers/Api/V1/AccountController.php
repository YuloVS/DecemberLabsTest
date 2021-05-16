<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AccountCollection;
use App\Http\Resources\V1\AccountResource;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    : AccountCollection
    {
        return new AccountCollection(Auth::user()->accounts);
    }

    public function show(Account $account)
    : AccountResource
    {
        $this->authorize("view", $account);
        return new AccountResource($account);
    }
}
