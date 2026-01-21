<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Refill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bundle_id', 'title','amount_mb','amount_days', 'price', 'sale_price', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

        public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}

