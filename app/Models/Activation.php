<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'iccid',
        'smdp_plus',
        'activation_code',
        'qr_code_url',
        'device_model',
        'device_os',
        'status',
        'activated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
