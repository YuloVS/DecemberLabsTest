<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Models\HouseAccountsRegistry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateBalances implements ShouldQueue
{
    public function handle(TransactionCreated $event)
    {
        DB::beginTransaction();
        $commissionCurrency = $event->transaction->originAccount->currency_id;
        $commissionAmount = config("transaction_commission") * $event->transaction->amount;
        $amountWithComission = $event->transaction->amount + $commissionAmount;
        $event->transaction->originAccount()->update([
                                                         "balance" => $event->transaction->originAccount->balance - $amountWithComission
                                                     ]);
        $event->transaction->destinationAccount()->update([
                                                              "balance" => $event->transaction->destinationAccount->balance + $event->transaction->converted
                                                          ]);
        $houseAccount = HouseAccountsRegistry::getAccountOfCurrency($commissionCurrency);
        $houseAccount->update([
            "balance" => $houseAccount->balance + $commissionAmount
                              ]);
        $event->transaction->update(["complete" => Carbon::now()]);
        DB::commit();
    }
}
