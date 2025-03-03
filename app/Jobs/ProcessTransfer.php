<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transfer;
use DB;
use Log;
use App\Models\User;
use App\Models\Transaction;

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transfer;

    protected $transaction1;

    protected $transaction2;

    public function __construct(Transfer $transfer, Transaction $transaction1, Transaction $transaction2)
    {
        $this->transfer = $transfer;
        $this->transaction1 = $transaction1;
        $this->transaction2 = $transaction2;
    }

    public function handle()
    {
        $transfer = $this->transfer;
        $sender = User::find($transfer->sender_id);
        $receiver = User::find($transfer->receiver_id);

        if ($sender->wallet->amount >= $transfer->amount) {
            $sender->wallet->decrement('amount', $transfer->amount);
            $receiver->wallet->increment('amount', $transfer->amount);
            $transfer->update(['status' => 'completed']);
            $this->transaction1->update(['status' => 'completed']);
            $this->transaction1->update(['status' => 'completed']);
            Log::info('Transfer completed: ' . $transfer->id);
        } else {
            $transfer->update(['status' => 'failed']);
            $this->transaction1->update(['status' => 'failed']);
            $this->transaction1->update(['status' => 'failed']);
            Log::error('Transfer failed due to insufficient balance: ' . $transfer->id);
        }
    }
}
