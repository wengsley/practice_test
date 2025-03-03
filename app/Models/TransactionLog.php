<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $primaryKey = "id";
    protected $table = 'transaction_logs';
    protected $hidden = [];
    protected $guarded = [];    
}
