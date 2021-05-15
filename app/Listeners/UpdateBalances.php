<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateBalances implements ShouldQueue
{
    public function handle(TransactionCreated $event)
    {
        DB::beginTransaction();
        $event->transaction->originAccount()->update([
            "balance" => $event->transaction->originAccount->balance - $event->transaction->amount
                                                     ]);
        $event->transaction->destinationAccount()->update([
                                                         "balance" => $event->transaction->destinationAccount->balance + $event->transaction->converted
                                                     ]);
        $event->transaction->update(["complete" => Carbon::now()]);
        DB::commit();
    }
}
