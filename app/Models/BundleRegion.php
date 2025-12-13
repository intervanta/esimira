<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BundleRegion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bundle_regions';

    protected $fillable = [
        'id',
        'name',
        'status',
    ];

    protected $casts = [
        'countries' => 'array',
        'status' => 'boolean',
    ];

    public function bundles()
    {
        return $this->hasMany(Bundle::class, 'region_id');
    }

    public function scopeHasActiveBundle($query)
{
    return $query->whereHas('bundles', fn($q) => $q->active());
}
}
