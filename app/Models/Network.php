<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Network extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bundle_id', 'country_id', 'title','amount_mb','amount_days','local_networks', 'flag_image_url', 'status'
    ];

    protected $casts = [
        'local_networks' => 'array',
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
