<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $primaryKey = "id";
    protected $table = 'wallet';
    protected $hidden = [];
    protected $guarded = [];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
