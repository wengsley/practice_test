<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Transaction;

class TransactionStatusChanged
{
    use Dispatchable, SerializesModels;

    public $transaction;
    public $oldStatus;
    public $newStatus;
    public $updated_at;
    public $created_at;

    public function __construct(Transaction $transaction, $oldStatus, $newStatus, $created_at, $updated_at)
    {
        $this->transaction = $transaction;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        
    }
}

