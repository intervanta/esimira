<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'order_id',
        'customer_id',
        'type',
        'source',
        'amount',
        'balance_after',
        'description'
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}


