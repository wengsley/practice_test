<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\TransactionStatusChanged;


class Transaction extends Model
{
    protected $primaryKey = "id";
    protected $table = 'transaction';
    protected $hidden = [];
    protected $guarded = [];    

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($transaction) {
            if ($transaction->isDirty('status')) {
                event(new TransactionStatusChanged(
                    $transaction,
                    $transaction->getOriginal('status'),
                    $transaction->status,
                    $transaction->created_at,
                    $transaction->updated_at
                ));
            }
        });
    }
}
