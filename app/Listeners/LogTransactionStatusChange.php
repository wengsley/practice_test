<?php

namespace App\Listeners;

use App\Events\TransactionStatusChanged;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionLog;

class LogTransactionStatusChange
{
    public function handle(TransactionStatusChanged $event)
    {
        TransactionLog::create([
            'transaction_id' => $event->transaction->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'changed_at' => $event->updated_at,
            'updated_at' => $event->updated_at,
            'created_at' => $event->updated_at
        ]);

        Log::info("Transaction ID: {$event->transaction->id} status changed from {$event->oldStatus} to {$event->newStatus}");
    }
}
