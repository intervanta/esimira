<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAmount extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'plan_amount',
        'convenience_fee',
        'gst_amount',
        'discount_amount',
        'final_amount',
        'currency',
        'converted_plan_amount',
        'converted_convenience_fee',
        'converted_gst_amount',
        'converted_discount_amount',
        'converted_final_amount',
        'converted_currency',
    ];
}
