<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $primaryKey = "id";
    protected $table = 'transfer';
    protected $hidden = [];
    protected $guarded = [];    
}
