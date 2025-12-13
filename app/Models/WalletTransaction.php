<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'customer_id',
        'type',
        'source',
        'amount',
        'balance_after',
        'description'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}


