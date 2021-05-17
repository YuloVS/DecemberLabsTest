<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TransactionCollection;
use App\Http\Resources\V1\TransactionResource;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    : TransactionCollection
    {
        $request->validate([
            "From" => "nullable|date",
            "To" => "nullable|date|after:From",
            "SourceAccountID" => "nullable|integer"
                           ]);
        $transactions = Auth::user()->transactions($request->From, $request->To, $request->SourceAccountID)["transactions"];
        return new TransactionCollection($transactions);
    }

    public function store(Request $request)
    : TransactionResource
    {
        $request->validate([
            "body.accountFrom" => "required|exists:accounts,id",
            "body.accountTo" => "required|exists:accounts,id|different:accountFrom",
            "body.amount" => "required|numeric",
            "body.date" => "required|date",
            "body.description" => "required|max:140"
                           ]);
        $account = Auth::user()->accounts->where("id", "=", $request->body["accountFrom"]);
        if($account->count() == 0)
        {
            abort(403, "Unauthorized");
        }
        if($account->pluck("balance")[0] < $request->body["amount"])
        {
            abort(403, "Insufficient funds");
        }
        return new TransactionResource(
            $account->first()
                ->makeTransaction(Account::find($request->body["accountTo"]), $request->body["amount"], $request->body["description"], $request->body["date"])
        );
    }
}
