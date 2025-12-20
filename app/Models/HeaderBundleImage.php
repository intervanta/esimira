<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderBundleImage extends Model
{
    protected $fillable = [
        'bundle_id',
        'type',
        'title',
        'image_url',
        'bundle_type',
        'source',
        'photographer_name',
        'photographer_link',
        'synced_at'
    ];

    protected $casts = [
        'synced_at' => 'datetime'
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
}